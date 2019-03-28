<?php declare(strict_types=1);


namespace Swoft\Rpc\Client;

use Swoft\Rpc\Client\Exception\RpcClientException;

/**
 * Class ReferenceRegister
 *
 * @since 2.0
 */
class ReferenceRegister
{
    /**
     * @var array
     */
    private static $references = [];

    /**
     * @param string $className
     * @param string $pool
     */
    public static function registerPool(string $className, string $pool)
    {
        self::$references[$className] = $pool;
    }

    /**
     * @param string $className
     *
     * @return string
     * @throws RpcClientException
     */
    public static function getPool(string $className): string
    {
        if (!isset(self::$references[$className])) {
            throw new RpcClientException(
                sprintf('`@Reference`(%s) is not exist!', $className)
            );
        }

        return self::$references[$className];
    }
}