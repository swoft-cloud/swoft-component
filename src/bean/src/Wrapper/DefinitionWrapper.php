<?php

/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
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
