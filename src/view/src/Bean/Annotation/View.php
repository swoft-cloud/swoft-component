<?php

namespace Swoft\View\Bean\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Action method annotation
 *
 * @Annotation
 * @Target("METHOD")
 */
class View
{

    /**
     * @var string
     */
    private $template = '';

    /**
     * @var string
     */
    private $layout = '';

    /**
     * View constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        isset($values['value']) && $this->setTemplate($values['value']);
        isset($values['template']) && $this->setTemplate($values['template']);
        isset($values['layout']) && $this->setLayout($values['layout']);
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * @param string $template
     *
     * @return self
     */
    public function setTemplate(string $template): self
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return string
     */
    public function getLayout(): string
    {
        return $this->layout;
    }

    /**
     * @param string $layout
     *
     * @return self
     */
    public function setLayout(string $layout): self
    {
        $this->layout = $layout;

        return $this;
    }
}
