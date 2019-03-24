<?php declare(strict_types=1);

namespace Swoft;

use Swoft\Context\Context;
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
     * -1   Not in coroutine
     * > -1 In coroutine
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
     * @param bool     $wait
     *
     * @return int If success, return coID
     */
    public static function create(callable $callable, bool $wait = true): int
    {
        $tid = self::tid();

        // return coroutine ID for created.
        return \go(function () use ($callable, $tid, $wait) {
            try {
                $id = Coroutine::getCid();

                self::$mapping[$id] = $tid;

                if ($wait) {
                    Context::getWaitGroup()->add();
                }

                PhpHelper::call($callable);
            } catch (\Throwable $e) {
                var_dump($e->getMessage(), ' file=' . $e->getFile() . ' line=' . $e->getLine());
            }

            if ($wait) {
                Context::getWaitGroup()->done();

//                \Swoft::trigger(SwoftEvent::COROUTINE_DEFER);
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
     * @return int
     */
    public static function writeFile(string $filename, string $data, int $flags = null): int
    {
        return Coroutine::writeFile($filename, $data, $flags);
    }
}
