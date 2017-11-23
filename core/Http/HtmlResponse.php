<?php


namespace Core\Http;


use Core\Contracts\View\ViewInterface;

class HtmlResponse extends Response
{
    /**
     * View of this response.
     * @var ViewInterface
     */
    private $view;

    /**
     * HtmlResponse constructor.
     * @param ViewInterface $view
     */
    public function __construct(ViewInterface $view, array $data = [])
    {
        parent::__construct($data);
        $this->view = $view;
    }

    /**
     * Send content.
     */
    protected function sendContent()
    {
        echo $this->view->render($this->data());
    }

    /**
     * Get or set the response view.
     * @param ViewInterface|null $view
     * @return null|ViewInterface
     */
    public function view(ViewInterface $view = null)
    {
        if (\is_null($view)) {
            return $this->view;
        }

        $this->view = $view;
    }
}