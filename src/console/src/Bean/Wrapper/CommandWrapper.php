<?php

/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Console\Bean\Wrapper;

use Swoft\Bean\Wrapper\AbstractWrapper;
use Swoft\Console\Bean\Annotation\Command;
use Swoft\Console\Bean\Annotation\Mapping;

class CommandWrapper extends AbstractWrapper
{
    protected $classAnnotations = [
        Command::class,
    ];

    protected $methodAnnotations = [
        Mapping::class
    ];

    /**
     * Whether to parse the class annotation
     */
    public function isParseClassAnnotations(array $annotations): bool
    {
        return isset($annotations[Command::class]);
    }

    /**
     * Whether to parse the attribute annotation
     */
    public function isParsePropertyAnnotations(array $annotations): bool
    {
        return false;
    }

    /**
     * Whether to parse method annotations
     */
    public function isParseMethodAnnotations(array $annotations): bool
    {
        return isset($annotations[Mapping::class]);
    }
}
