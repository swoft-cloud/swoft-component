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
     */
    private static $handlers = [];

    /**
     * @param string $handlerClass
     * @param int    $priority
     */
    public static function collect(string $handlerClass, int $priority): void
    {
        self::$handlers[$handlerClass] = $priority;
    }

    /**
     * @param ErrorHandlerChain $chain
     * @return int
     */
    public static function register(ErrorHandlerChain $chain): int
    {
        $count = \count(self::$handlers);

        foreach (self::$handlers as $handler => $priority) {
            $chain->addHandler(new $handler, $priority);
        }

        // Clear data
        self::$handlers = [];
        return $count;
    }
}
