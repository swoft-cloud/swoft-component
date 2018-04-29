<?php

namespace Swoft\Task\Bean\Wrapper;

use Swoft\Bean\Annotation\Inject;
use Swoft\Bean\Annotation\Value;
use Swoft\Bean\Wrapper\AbstractWrapper;
use Swoft\Task\Bean\Annotation\Task;
use Swoft\Task\Bean\Annotation\Scheduled;

/**
 * Task annotation wrapper
 */
class TaskWrapper extends AbstractWrapper
{
    /**
     * Class annotation
     *
     * @var array
     */
    protected $classAnnotations = [
        Task::class
    ];

    /**
     * Property annotations
     *
     * @var array
     */
    protected $propertyAnnotations = [
        Inject::class,
        Value::class,
    ];

    /**
     * Method annotation
     *
     * @var array
     */
    protected $methodAnnotations = [
        Scheduled::class
    ];

    /**
     * @param array $annotations
     * @return bool
     */
    public function isParseClassAnnotations(array $annotations): bool
    {
        return isset($annotations[Task::class]);
    }

    /**
     * @param array $annotations
     * @return bool
     */
    public function isParsePropertyAnnotations(array $annotations): bool
    {
        return isset($annotations[Inject::class]) || isset($annotations[Value::class]);
    }

    /**
     * @param array $annotations
     * @return bool
     */
    public function isParseMethodAnnotations(array $annotations): bool
    {
        return isset($annotations[Scheduled::class]);
    }
}
