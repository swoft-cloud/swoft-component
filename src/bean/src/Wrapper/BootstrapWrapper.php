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

use Swoft\Bean\Annotation\Bootstrap;
use Swoft\Bean\Annotation\Inject;

class BootstrapWrapper extends AbstractWrapper
{
    protected $classAnnotations = [
        Bootstrap::class,
    ];

    protected $propertyAnnotations = [
        Inject::class,
    ];

    public function isParseClassAnnotations(array $annotations): bool
    {
        return isset($annotations[Bootstrap::class]);
    }

    public function isParsePropertyAnnotations(array $annotations): bool
    {
        return isset($annotations[Inject::class]);
    }

    public function isParseMethodAnnotations(array $annotations): bool
    {
        return false;
    }
}
