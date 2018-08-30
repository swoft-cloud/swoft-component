<?php

namespace Swoft\Bean\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
class Definition
{
    /**
     * @var string
     */
    private $name = '';

    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->name = $values['value'];
        }

        if (isset($values['name'])) {
            $this->name = $values['name'];
        }
    }

    public function getName(): string
    {
        return $this->name;
    }
}