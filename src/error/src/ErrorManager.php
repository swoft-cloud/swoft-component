<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Error;

use Swoft;
use Swoft\Bean\Annotation\Mapping\Bean;
use Throwable;
use function count;
use function get_class;

/**
 * Class ErrorManager
 *
 * @since 2.0
 *
 * @Bean()
 */
class ErrorManager
{
    /**
     * @var int
     */
    private $count = 0;

    /**
     * @var array
     * [
     *  exception class => handler class,
     *  ... ...
     * ]
     */
    private $handlers = [];

    /**
     * Add a handler class to chains
     *
     * @param string $exceptionClass
     * @param string $handlerClass
     * @param int    $type
     */
    public function addHandler(string $exceptionClass, string $handlerClass, int $type = ErrorType::DEF): void
    {
        $this->count++;
        $this->handlers[$type][$exceptionClass] = $handlerClass;
    }

    /**
     * Match error handler by type and exception object.
     *
     * TODO add third arg, for check handler implemented interface class is right? eg: MessageErrorHandlerInterface
     *
     * @param Throwable $e
     * @param int       $type
     *
     * @return mixed|null If match ok, will return handler object.
     */
    public function match(Throwable $e, int $type = ErrorType::DEF)
    {
        // No handlers found
        if (!isset($this->handlers[$type]) || $this->getCount() === 0) {
            return null;
        }

        $errClass = get_class($e);
        $handlers = $this->handlers[$type];

        if (isset($handlers[$errClass])) {
            return Swoft::getSingleton($handlers[$errClass]);
        }

        $handler = null;
        foreach ($handlers as $exceptionClass => $handlerClass) {
            if ($e instanceof $exceptionClass) {
                $handler = Swoft::getSingleton($handlerClass);
                break;
            }
        }

        return $handler;
    }

    /**
     * @param Throwable $e
     * @param int       $type
     *
     * @return mixed|null If match ok, will return handler object.
     * @deprecated please use match() instead.
     */
    public function matchHandler(Throwable $e, int $type = ErrorType::DEF)
    {
        return $this->match($e, $type);
    }

    /**
     * @return int
     */
    public function getTypeCount(): int
    {
        return count($this->handlers);
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * Clear handler chains
     *
     * @return void
     */
    public function clear(): void
    {
        $this->count    = 0;
        $this->handlers = [];
    }
}
