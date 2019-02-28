<?php declare(strict_types=1);


namespace Swoft;


use Swoft\Stdlib\Helper\PhpHelper;
use Swoole\Coroutine;

/**
 * Class Co
 * @since   2.0
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
     *
     * @return int If success, return coID
     */
    public static function create(callable $callable): int
    {
        $tid = self::tid();

        // return coroutine ID for created.
        return \go(function () use ($callable, $tid) {
            try {
                $id = Coroutine::getCid();

                self::$mapping[$id] = $tid;
                PhpHelper::call($callable);
            } catch (\Throwable $e) {
                var_dump($e);
            }
        });
    }

    /**
     * Write file
     *
     * @param string   $filename
     * @param string   $data
     * @param int|null $flags
     *
     * @return bool
     */
    public static function writeFile(string $filename, string $data, int $flags = null): bool
    {
        return Coroutine::writeFile($filename, $data, $flags);
    }
}
