<?php
namespace Dwoo\SymfonyBundle\CacheWarmer;

use Symfony\Bundle\FrameworkBundle\CacheWarmer\TemplateFinderInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Templating\TemplateNameParserInterface;

/**
 * DwooTemplateFinder, finds all the templates.
 *
 * @package Dwoo\SymfonyBundle\CacheWarmer
 */
class DwooTemplateFinder implements TemplateFinderInterface
{
    protected $kernel;
    protected $parser;
    protected $rootDir;
    protected $templates;

    /**
     * DwooTemplateFinder constructor.
     *
     * @param KernelInterface             $kernel
     * @param TemplateNameParserInterface $parser
     * @param string                      $rootDir
     */
    public function __construct(KernelInterface $kernel, TemplateNameParserInterface $parser, $rootDir)
    {
        $this->kernel  = $kernel;
        $this->parser  = $parser;
        $this->rootDir = $rootDir;
    }

    /**
     * Find all the templates.
     *
     * @return array An array of templates of type TemplateReferenceInterface
     */
    public function findAllTemplates()
    {
        if (null !== $this->templates) {
            return $this->templates;
        }

        $templates = [];

        foreach ($this->kernel->getBundles() as $name => $bundle) {
            $templates = array_merge($templates, $this->findTemplatesInBundle($bundle));
        }

        $templates = array_merge($templates, $this->findTemplatesInFolder($this->rootDir . '/views'));

        return $this->templates = $templates;
    }

    /**
     * Find Dwoo templates in the given directory.
     *
     * @param string $dir The folder where to look for templates
     *
     * @return array An array of templates of type TemplateReferenceInterface
     */
    public function findTemplatesInFolder($dir)
    {
        $templates = [];
        if (is_dir($dir)) {
            $finder = new Finder();
            foreach ($finder->files()->followLinks()->in($dir) as $file) {
                $template = $this->parser->parse($file->getRelativePathname());
                if (false !== $template && in_array($template->get('engine'), ['dwoo', 'tpl'])) {
                    $templates[] = $template;
                }
            }
        }

        return $templates;
    }

    /**
     * Find templates in the given bundle.
     *
     * @param BundleInterface $bundle The bundle where to look for templates
     *
     * @return array An array of templates of type TemplateReferenceInterface
     */
    public function findTemplatesInBundle(BundleInterface $bundle)
    {
        $templates = $this->findTemplatesInFolder($bundle->getPath() . '/Resources/views');
        $name      = $bundle->getName();
        foreach ($templates as $i => $template) {
            $templates[$i] = $template->set('bundle', $name);
        }

        return $templates;
    }
}