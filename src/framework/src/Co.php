<?php declare(strict_types=1);

namespace Swoft;

use function count;
use function go;
use ReflectionException;
use function sgo;
use Swoft;
use Swoft\Context\Context;
use Swoft\Log\Debug;
use Swoft\Stdlib\Helper\PhpHelper;
use Swoole\Coroutine;
use Throwable;

/**
 * Class Co
 * @since   2.0
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
        return go(function () use ($callable, $tid, $wait) {
            try {
                $id = Coroutine::getCid();
                // Storage fd
                self::$mapping[$id] = $tid;

                if ($wait) {
                    Context::getWaitGroup()->add();
                }

                PhpHelper::call($callable);
            } catch (Throwable $e) {
                Debug::log(
                    "Coroutine internal error: %s\nAt File %s line %d\nTrace:\n%s",
                    $e->getMessage(),
                    $e->getFile(),
                    $e->getLine(),
                    $e->getTraceAsString()
                );
            }

            if ($wait) {
                // Trigger defer
                Swoft::trigger(SwoftEvent::COROUTINE_DEFER);

                Context::getWaitGroup()->done();
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

    /**
     * Read file
     *
     * @param string $filename
     *
     * @return string
     */
    public static function readFile(string $filename): string
    {
        return Coroutine::readFile($filename);
    }

    /**
     * Multi request
     *
     * @param array $requests
     * @param float $timeout
     *
     * @return array
     * @throws Bean\Exception\ContainerException
     * @throws ReflectionException
     */
    public static function multi(array $requests, float $timeout = 0): array
    {
        $count   = count($requests);
        $channel = new Coroutine\Channel($count);

        foreach ($requests as $key => $callback) {
            sgo(function () use ($key, $channel, $callback) {
                try {
                    $data = PhpHelper::call($callback);
                    $channel->push([$key, $data]);
                } catch (Throwable $e) {
                    Debug::log(
                        'Co multi errro(key=%s) is %s', $key, $e->getMessage()
                    );

                    $channel->push(false);
                }
            });
        }

        $response = [];
        while ($count > 0) {
            $result = $channel->pop($timeout);
            if ($result === false) {
                Debug::log('Co::multi request fail!');
            } else {
                [$key, $value] = $result;
                $response[$key] = $value;
            }

            $count--;
        }

        return $response;
    }
}
