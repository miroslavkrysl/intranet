<?php


namespace Core\View;


use Core\Contracts\View\ViewInterface;


/**
 * View implementation using symfony/twig.
 */
class TwigView implements ViewInterface
{
    /**
     * @var \Twig_TemplateWrapper
     */
    private $templateWrapper;

    /**
     * TwigView constructor.
     * @param \Twig_TemplateWrapper $templateWrapper
     */
    public function __construct(\Twig_TemplateWrapper $templateWrapper)
    {
        $this->templateWrapper = $templateWrapper;
    }

    /**
     * Render the view with given data.
     * @param array $data
     * @return string
     */
    public function render(array $data = []): string
    {
        return $this->templateWrapper->render($data);
    }
}