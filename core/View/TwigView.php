<?php


namespace Core\View;


use Core\Container\Container;
use Core\Contracts\View\ViewInterface;
use Twig_Environment;
use Twig_Loader_Filesystem;


/**
 * View implementation using symfony/twig.
 */
class TwigView implements ViewInterface
{
    /**
     * @var Twig_Environment
     */
    private $twigEnv;

    /**
     * Global container instance.
     * @var Container
     */
    private $container;

    /**
     * TwigView constructor.
     */
    public function __construct(Container $container, string $viewsFolderPath)
    {
        $this->container = $container;
        $loader = new Twig_Loader_Filesystem($viewsFolderPath);
        $this->twigEnv = new Twig_Environment($loader);
        $this->twigEnv->addFunction(new \Twig_Function('_text', 'text'));
        $this->twigEnv->addFunction(new \Twig_Function('_config', 'config'));
    }

    /**
     * Find the specified view, push the data to the view and return result.
     * @param string $name
     * @param array $data
     * @return string
     */
    public function render(string $name, array $data = []): string
    {
        $name = \preg_replace('/\./', '/', $name);
        $name .= '.twig';
        return $this->twigEnv->render($name, $data);
    }
}