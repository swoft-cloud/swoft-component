<?php

namespace Swoft\Console\Bean\Wrapper;

use Swoft\Bean\Wrapper\AbstractWrapper;
use Swoft\Console\Bean\Annotation\Command;
use Swoft\Console\Bean\Annotation\Mapping;

/**
 * the wrapper of command
 * @author stelin <phpcrazy@126.com>
 */
class CommandWrapper extends AbstractWrapper
{
    /**
     * Class annotation
     *
     * @var array
     */
    protected $classAnnotations = [
        Command::class,
    ];

    /**
     * Method annotation
     *
     * @var array
     */
    protected $methodAnnotations = [
        Mapping::class
    ];

    /**
     * Whether to parse the class annotation
     *
     * @param array $annotations
     * @return bool
     */
    public function isParseClassAnnotations(array $annotations): bool
    {
        return isset($annotations[Command::class]);
    }

    /**
     * Whether to parse the attribute annotation
     *
     * @param array $annotations
     * @return bool
     */
    public function isParsePropertyAnnotations(array $annotations): bool
    {
        return false;
    }

    /**
     * Whether to parse method annotations
     *
     * @param array $annotations
     * @return bool
     */
    public function isParseMethodAnnotations(array $annotations): bool
    {
        return isset($annotations[Mapping::class]);
    }
}
