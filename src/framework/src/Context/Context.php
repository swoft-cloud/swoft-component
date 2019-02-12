<?php declare(strict_types=1);


namespace Swoft\Context;

use Swoft\Co;
use Swoft\Http\Server\HttpContext;
use Swoft\WebSocket\Server\Connection;

/**
 * Class Context
 *
 * @since 2.0
 */
class Context
{
    /**
     * Context
     *
     * @var ContextInterface[]
     *
     * @example
     * [
     *    'tid' => ContextInterface,
     *    'tid2' => ContextInterface,
     *    'tid3' => ContextInterface,
     * ]
     */
    private static $context = [];

    /**
     * Get context
     *
     * @return ContextInterface|HttpContext|Connection
     */
    public static function get(): ?ContextInterface
    {
        $tid = Co::tid();

        return self::$context[$tid] ?? null;
    }

    /**
     * Set context
     *
     * @param ContextInterface $context
     */
    public static function set(ContextInterface $context): void
    {
        $tid = Co::tid();

        self::$context[$tid] = $context;
    }

    /**
     * Destroy context
     */
    public static function destroy(): void
    {
        $tid = Co::tid();

        if (isset(self::$context[$tid])) {
            $ctx = self::$context[$tid];
            $ctx->clear();

            unset(self::$context[$tid], $ctx);
        }
    }
}