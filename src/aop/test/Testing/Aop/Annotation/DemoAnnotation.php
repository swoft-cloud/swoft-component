<?php

namespace SwoftTest\Aop\Testing\Aop\Annotation;

/**
 * Class DemoAnnotation
 * @Annotation
 * @Target("METHOD")
 */
class DemoAnnotation
{
    /**
     * @var string
     */
    private $name;

    public function __construct(array $values)
    {
        if (isset($values['name'])) {
            $this->name = $values['name'];
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}