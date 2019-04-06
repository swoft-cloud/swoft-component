<?php declare(strict_types=1);


namespace Swoft\Task;


class Task
{
    /**
     * Coroutine
     */
    public const CO = 'co';

    /**
     * Async
     */
    public const ASYNC = 'async';

    /**
     * @param string $name
     * @param string $method
     * @param array  $params
     * @param int    $timeout
     *
     * @return bool
     */
    public static function co(string $name, string $method, array $params = [], float $timeout = 3): bool
    {
        return false;
    }

    /**
     * @param string $name
     * @param string $method
     * @param array  $params
     * @param int    $dstWorkerId
     *
     * @return bool
     */
    public static function async(string $name, string $method, array $params = [], int $dstWorkerId = -1): bool
    {
        return false;
    }

    /**
     * @param array $tasks
     * @param float $timeout
     *
     * @return array
     */
    public static function cos(array $tasks, float $timeout = 3): array
    {

    }
}