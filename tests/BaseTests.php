<?php
namespace Dwoo\SymfonyBundle\Tests
{

    use Dwoo\Core;
    use Dwoo\SymfonyBundle\DwooEngine;
    use Symfony\Component\DependencyInjection\ContainerBuilder;
    use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
    use Symfony\Component\Templating\TemplateNameParser;
    use Symfony\Component\Templating\Loader\Loader;
    use Symfony\Component\Templating\TemplateReferenceInterface;
    use Symfony\Component\Templating\TemplateReference;
    use Symfony\Component\Templating\Storage\StringStorage;

    /**
     * Class BaseTests
     *
     * @package Dwoo\SymfonyBundle\Tests
     */
    class BaseTests extends \PHPUnit_Framework_TestCase
    {

        protected $core;
        protected $loader;

        protected function setUp()
        {
            if (!class_exists('\Dwoo\Core')) {
                $this->markTestSkipped('Dwoo is not available.');
            }

            $this->core   = new Core();
            $this->loader = new ProjectTemplateLoader();
        }

        public function getDwooOptions()
        {
            return [
                'compile_dir' => __DIR__ . '/compile'
            ];
        }

        public function getDwooEngine(array $options = [], $global = null, $logger = null)
        {
            $container = $this->createContainer();
            $options   = array_merge($this->getDwooOptions(), $options);

            return new ProjectTemplateEngine($this->core, $container, new TemplateNameParser(), $this->loader, $options,
                $global, $logger);
        }

        protected function createContainer(array $data = [])
        {
            return new ContainerBuilder(new ParameterBag(array_merge([
                'kernel.bundles'          => ['DwooBundle' => 'Dwoo\\SymfonyBundle\\DwooBundle'],
                'kernel.cache_dir'        => __DIR__,
                'kernel.compiled_classes' => [],
                'kernel.debug'            => false,
                'kernel.environment'      => 'test',
                'kernel.name'             => 'kernel',
                'kernel.root_dir'         => __DIR__,
            ], $data)));
        }
    }

    /**
     * Class ProjectTemplateEngine
     *
     * @package Dwoo\SymfonyBundle\Tests
     */
    class ProjectTemplateEngine extends DwooEngine
    {
        public function setTemplate($name, $content)
        {
            $this->loader->setTemplate($name, $content);
        }

        public function getLoader()
        {
            return $this->loader;
        }
    }

    /**
     * Class ProjectTemplateLoader
     *
     * @package Dwoo\SymfonyBundle\Tests
     */
    class ProjectTemplateLoader extends Loader
    {
        public $templates = [];

        public function setTemplate($name, $content)
        {
            $template                                     = new TemplateReference($name, 'dwoo');
            $this->templates[$template->getLogicalName()] = $content;
        }

        public function load(TemplateReferenceInterface $template)
        {
            if (isset($this->templates[$template->getLogicalName()])) {
                $storage = new StringStorage($this->templates[$template->getLogicalName()]);

                return 'string:' . $storage->getContent();
            }

            return false;
        }

        public function isFresh(TemplateReferenceInterface $template, $time)
        {
            return false;
        }
    }
}