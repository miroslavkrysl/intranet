<?php


namespace Core\View;


use Core\Contracts\View\ViewInterface;
use Twig_Environment;
use Twig_Loader_Filesystem;


/**
 * View implementation using symfony/twig.
 */
class TwigView implements ViewInterface
{
    private $twigEnv;

    /**
     * TwigView constructor.
     */
    public function __construct(string $viewsFolderPath)
    {
        $loader = new Twig_Loader_Filesystem($viewsFolderPath);
        $this->twigEnv = new Twig_Environment($loader);
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
        $name .= '.php';
        return $this->twigEnv->render($name, $data);
    }
}