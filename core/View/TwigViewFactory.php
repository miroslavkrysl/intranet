<?php


namespace Core\View;


use Core\Contracts\View\ViewFactoryInterface;
use Core\Contracts\View\ViewInterface;
use Twig_Environment;
use Twig_Loader_Filesystem;


class TwigViewFactory implements ViewFactoryInterface
{
    /**
     * @var Twig_Environment
     */
    private $twigEnv;

    /**
     * TwigView constructor.
     * @param string $viewsFolderPath
     */
    public function __construct(string $viewsFolderPath)
    {
        $loader = new Twig_Loader_Filesystem($viewsFolderPath);
        $this->twigEnv = new Twig_Environment($loader);
    }

    /**
     * Find the specified view.
     * @param string $name
     * @return ViewInterface
     */
    public function view(string $name): ViewInterface
    {
        $name = \preg_replace('/\./', '/', $name);
        $name .= '.twig';

        $template = $this->twigEnv->load($name);

        return new TwigView($template);
    }

    /**
     * Register function for using it in templates.
     */
    public function registerFunction(string $alias, callable $function)
    {
        $this->twigEnv->addFunction(new \Twig_Function($alias, $function));
    }

    /**
     * Register global for using it in templates.
     */
    public function registerGlobal(string $alias, $global)
    {
        $this->twigEnv->addGlobal($alias, $global);
    }
}