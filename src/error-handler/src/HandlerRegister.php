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
     *  handler class => [exception class, exception class1],
     * ]
     */
    private static $handlers = [];

    /**
     * @param string $handlerClass
     * @param array  $exceptions
     */
    public static function add(string $handlerClass, array $exceptions): void
    {
        self::$handlers[$handlerClass] = $exceptions;
    }

    /**
     * @param ErrorHandlerChain $chain
     */
    public static function register(ErrorHandlerChain $chain): void
    {
        foreach (self::$handlers as $handlerClass => $exceptions) {
            foreach ($exceptions as $exceptionClass) {
                $chain->addHandler($exceptionClass, $handlerClass);
            }
        }

        // Clear data
        self::$handlers = [];
    }
}
