<?php declare(strict_types=1);

namespace Swoft\ErrorHandler;

/**
 * Class HandlerRegister
 * @since 2.0
 */
final class HandlerRegister
{
    /**
     * @var array
     * [
     *  handler class => [priority, exception classes],
     * ]
     */
    private static $handlers = [];

    /**
     * @param string $handlerClass
     * @param int    $priority
     * @param array  $exceptions
     */
    public static function add(string $handlerClass, int $priority, array $exceptions): void
    {
        self::$handlers[$handlerClass] = [$priority, $exceptions];
    }

    /**
     * @param ErrorHandlerChain $chain
     */
    public static function register(ErrorHandlerChain $chain): void
    {
        foreach (self::$handlers as $handlerClass => [$priority, $exceptions]) {
            // $handler = \Swoft::getBean($handlerClass);

            foreach ($exceptions as $exceptionClass) {
                $chain->addHandler($exceptionClass, $handlerClass, $priority);
            }
        }

        // Clear data
        self::$handlers = [];
    }
}
