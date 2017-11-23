<?php


namespace Core\View;


use Core\Container\Container;
use Core\Contracts\Language\LanguageInterface;
use Core\Contracts\View\ViewInterface;
use Twig_Environment;
use Twig_Extension_Debug;
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
     * @var LanguageInterface
     */
    private $language;

    /**
     * TwigView constructor.
     * @param LanguageInterface $language
     * @param string $viewsFolderPath
     */
    public function __construct(LanguageInterface $language, string $viewsFolderPath)
    {
        $this->language = $language;

        $loader = new Twig_Loader_Filesystem($viewsFolderPath);
        $this->twigEnv = new Twig_Environment($loader);

        $this->registerFunctions();
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

    /**
     * Register function for using them in templates.
     */
    private function registerFunctions()
    {
        $this->twigEnv->addFunction(new \Twig_Function('_text', [$this->language, 'get']));
        $this->twigEnv->addFunction(new \Twig_Function('_config', 'config'));
        $this->twigEnv->addFunction(new \Twig_Function('_token', 'csrf_token'));
    }
}