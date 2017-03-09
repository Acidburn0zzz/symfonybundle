<?php
namespace Dwoo\SymfonyBundle;

use Dwoo\Core;
use Dwoo\Data;
use Dwoo\ITemplate;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Bundle\FrameworkBundle\Templating\GlobalVariables;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\TemplateNameParserInterface;
use Symfony\Component\Templating\TemplateReferenceInterface;
use Symfony\Component\Templating\Loader\LoaderInterface;

/**
 * DwooEngine is an engine able to render Dwoo templates.
 * This class is heavily inspired by \Twig_Environment.
 * See {@link http://twig.sensiolabs.org/doc/api.html} for details about \Twig_Environment.
 *
 * @package Dwoo\SymfonyBundle
 */
class DwooEngine implements EngineInterface
{

    /** @var Core */
    protected $core;

    /** @var TemplateNameParserInterface */
    protected $parser;

    /** @var LoaderInterface */
    protected $loader;

    /** @var  array */
    protected $globals = [];

    /**
     * DwooEngine constructor.
     *
     * @param Core                        $core    A Dwoo\Core instance
     * @param TemplateNameParserInterface $parser  A TemplateNameParserInterface instance
     * @param LoaderInterface             $loader  A LoaderInterface instance
     * @param array                       $options An array of \Dwoo\Core properties
     * @param GlobalVariables             $globals A GlobalVariables instance or null
     */
    public function __construct(Core $core, TemplateNameParserInterface $parser, LoaderInterface $loader,
                                array $options = [], GlobalVariables $globals = null)
    {
        $this->core   = $core;
        $this->parser = $parser;
        $this->loader = $loader;

        foreach ($options as $property => $value) {
            $this->core->{$this->propertyToSetter($property)}($value);
        }

        if (null !== $globals) {
            $this->addGlobal('app', $globals);
        }
    }

    /**
     * Renders a view and returns a Response.
     *
     * @param string   $view       The view name
     * @param array    $parameters An array of parameters to pass to the view
     * @param Response $response   A Response instance
     *
     * @return Response A Response instance
     * @throws \RuntimeException if the template cannot be rendered
     */
    public function renderResponse($view, array $parameters = [], Response $response = null)
    {
        if (null === $response) {
            $response = new Response();
        }

        $response->setContent($this->render($view, $parameters));

        return $response;
    }

    /**
     * Renders a template.
     *
     * @param string|TemplateReferenceInterface $name       A template name or a TemplateReferenceInterface instance
     * @param array                             $parameters An array of parameters to pass to the template
     *
     * @return string The evaluated template as a string
     * @throws \RuntimeException if the template cannot be rendered
     */
    public function render($name, array $parameters = [])
    {
        $template = $this->load($name);

        // attach the global variables
        $parameters = array_replace($this->globals, $parameters);

        /**
         * Assign variables/objects to the templates.
         */
        $data = new Data();
        $data->assign($parameters);

        try {
            return $this->core->get($template, $data);
        }
        catch (\Exception $e) {
            return sprintf('"%s"', $e->getMessage());
        }
    }

    /**
     * Returns true if the template exists.
     *
     * @param string|TemplateReferenceInterface $name A template name or a TemplateReferenceInterface instance
     *
     * @return bool true if the template exists, false otherwise
     * @throws \RuntimeException if the engine cannot handle the template name
     */
    public function exists($name)
    {
        try {
            $this->load($name);
        }
        catch (\InvalidArgumentException $e) {
            return false;
        }

        return true;
    }

    /**
     * Returns true if this class is able to render the given template.
     *
     * @param string|TemplateReferenceInterface $name A template name or a TemplateReferenceInterface instance
     *
     * @return bool true if this class supports the given template, false otherwise
     */
    public function supports($name)
    {
        if ($name instanceof ITemplate) {
            return true;
        }

        $template = $this->parser->parse($name);

        // Keep 'tpl' for backwards compatibility.
        return in_array($template->get('engine'), ['smarty', 'tpl'], true);
    }

    /**
     * Loads the given template.
     *
     * @param string $name A template name
     *
     * @return mixed The resource handle of the template file or template object
     * @throws \InvalidArgumentException if the template cannot be found
     */
    public function load($name)
    {
        if ($name instanceof ITemplate) {
            return $name;
        }

        $template = $this->parser->parse($name);
        $template = $this->loader->load($template, '');

        if (false === $template) {
            throw new \InvalidArgumentException(sprintf('The template "%s" does not exist.', $name));
        }

        return (string)$template;
    }

    /**
     * Registers a Global.
     *
     * @param string $name  The global name
     * @param mixed  $value The global value
     */
    public function addGlobal($name, $value)
    {
        $this->globals[$name] = $value;
    }

    /**
     * Get the setter method for a Dwoo class variable (property).
     * You may use this method to generate addSomeProperty() or getSomeProperty()
     * kind of methods by setting the $prefix parameter to "add" or "get".
     *
     * @param string $property
     * @param string $prefix
     *
     * @return string
     */
    protected function propertyToSetter($property, $prefix = 'set')
    {
        return $prefix . str_replace(' ', '', ucwords(str_replace('_', ' ', $property)));
    }
}