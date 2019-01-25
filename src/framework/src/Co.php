<?php declare(strict_types=1);


namespace Swoft;


use Swoft\Stdlib\Helper\PhpHelper;
use Swoole\Coroutine;

class Co
{
    /**
     * Coroutine id mappping
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
    public static function create(callable $callable)
    {
        $tid = self::tid();
        go(function () use ($callable, $tid) {

            $id = Coroutine::getCid();

            self::$mapping[$id] = $tid;
            PhpHelper::call($callable);
        });
    }
}