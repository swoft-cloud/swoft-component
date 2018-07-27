<?php

namespace SwoftTest\Aop\Annotation;

/**
 * Class DemoAnnotation
 * @Annotation
 * @Target("METHOD")
 * @package SwoftTest\Aop\Annotation
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