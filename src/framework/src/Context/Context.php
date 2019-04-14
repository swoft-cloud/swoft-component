<?php declare(strict_types=1);

namespace Swoft\Context;

use Swoft\Bean\BeanFactory;
use Swoft\Co;
use Swoft\Exception\ContextException;
use Swoft\Http\Server\HttpContext;
use Swoft\WebSocket\Server\Context\WsMessageContext;

/**
 * Class Context - request context manager
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
     * @return ContextInterface|HttpContext|WsMessageContext
     */
    public static function get(): ?ContextInterface
    {
        $tid = Co::tid();

        return self::$context[$tid] ?? null;
    }

    /**
     * Get context by coID, if not found will throw exception.
     *
     * @return ContextInterface|HttpContext|WsMessageContext
     */
    public static function mustGet(): ContextInterface
    {
        $tid = Co::tid();

        if (isset(self::$context[$tid])) {
            return self::$context[$tid];
        }

        throw new ContextException('context information has been lost of the coID: ' . $tid);
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
     * Get context wait group
     *
     * @return ContextWaitGroup
     *
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public static function getWaitGroup(): ContextWaitGroup
    {
        return BeanFactory::getBean(ContextWaitGroup::class);
    }

    /**
     * Destroy context
     */
    public static function destroy(): void
    {
        $tid = Co::tid();

        if (isset(self::$context[$tid])) {
            // clear self data.
            self::$context[$tid]->clear();
            unset(self::$context[$tid]);
        }
    }
}
