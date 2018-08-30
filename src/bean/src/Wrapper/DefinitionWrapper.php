<?php

namespace Swoft\Bean\Wrapper;

use Swoft\Bean\Annotation\Definition;

/**
 * The wrapper of definition
 */
class DefinitionWrapper extends AbstractWrapper
{

    protected $classAnnotations = [
        Definition::class,
    ];

    public function isParseClassAnnotations(array $annotations): bool
    {
        return isset($annotations[Definition::class]);
    }

    public function isParsePropertyAnnotations(array $annotations): bool
    {
        return false;
    }

    public function isParseMethodAnnotations(array $annotations): bool
    {
        return false;
    }
}