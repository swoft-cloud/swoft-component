<?php

namespace Swoft\Console\Bean\Annotation;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class Mapping
{
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