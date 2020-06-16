<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

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
     *
     * @example
     * [
     *     'className' => [
     *         'pool' => 'poolName',
     *         'version' => 'version',
     *     ]
     * ]
     */
    private static $references = [];

    /**
     * @param string $className
     * @param string $pool
     * @param string $version
     */
    public static function register(string $className, string $pool, string $version): void
    {
        self::$references[$className]['pool']    = $pool;
        self::$references[$className]['version'] = $version;
    }

    /**
     * @param string $className
     *
     * @return string
     * @throws RpcClientException
     */
    public static function getPool(string $className): string
    {
        $pool = self::$references[$className]['pool'] ?? '';
        if (empty($pool)) {
            throw new RpcClientException(sprintf('`@Reference` pool (%s) is not exist!', $className));
        }

        return $pool;
    }

    /**
     * @param string $className
     *
     * @return string
     * @throws RpcClientException
     */
    public static function getVersion(string $className): string
    {
        $version = self::$references[$className]['version'] ?? '';
        if ($version === '') {
            throw new RpcClientException(sprintf('`@Reference` version(%s) is not exist!', $className));
        }

        return $version;
    }
}
