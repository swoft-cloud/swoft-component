<?php declare(strict_types=1);


namespace Swoft;


use Swoft\Stdlib\Helper\PhpHelper;
use Swoole\Coroutine;

/**
 * Class Co
 * @since 2.0
 * @package Swoft
 */
class Co
{
    /**
     * Coroutine id mapping
     *
     * @var array
     * @example
     * [
     *    'child id'  => 'top id',
     *    'child id'  => 'top id',
     *    'child id'  => 'top id'
     * ]
     */
    private static $mapping = [];

    /**
     * Get current coroutine id
     *
     * @return int
     */
    public static function id(): int
    {
        return Coroutine::getCid();
    }

    /**
     * Get the top coroutine ID
     *
     * @return int
     */
    public static function tid(): int
    {
        $id = self::id();
        return self::$mapping[$id] ?? $id;
    }

    /**
     * Create coroutine
     *
     * @param callable $callable
     */
    public static function create(callable $callable): void
    {
        $tid = self::tid();

        // return coroutine ID for created.
        \go(function () use ($callable, $tid) {

            $id = Coroutine::getCid();

            self::$mapping[$id] = $tid;
            PhpHelper::call($callable);
        });
    }
}