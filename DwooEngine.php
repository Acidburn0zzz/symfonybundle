<?php
namespace Dwoo\SymfonyBundle;

use Dwoo\Core;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Bundle\FrameworkBundle\Templating\GlobalVariables;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\TemplateReferenceInterface;

/**
 * Class DwooEngine
 *
 * @package Dwoo\SymfonyBundle
 */
class DwooEngine implements EngineInterface
{

    /** @var Core */
    protected $core;

    /** @var  array */
    protected $globals = [];

    /**
     * DwooEngine constructor.
     *
     * @param Core            $core    A Dwoo\Core instance
     * @param array           $options A ContainerInterface instance
     * @param GlobalVariables $globals A GlobalVariables instance or null
     */
    public function __construct(Core $core, array $options = [], GlobalVariables $globals = null)
    {
        $this->core = $core;

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
        // TODO: Implement renderResponse() method.
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
        // TODO: Implement render() method.
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
        // TODO: Implement exists() method.
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
        // TODO: Implement supports() method.
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