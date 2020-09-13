<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Aop;

use Swoft\Aop\Ast\Visitor\ProxyVisitor;
use Swoft\Proxy\Exception\ProxyException;
use Swoft\Proxy\Proxy as BaseProxy;
use function explode;
use function strpos;

/**
 * Class Proxy
 *
 * @since 2.0
 */
class Proxy
{
    /**
     * New class name by proxy
     *
     * @param string $className
     *
     * @return string
     * @throws ProxyException
     */
    public static function newClassName(string $className): string
    {
        // Only proxy AOP class
        if (!Aop::matchClass($className)) {
            return $className;
        }

        // Is RPC proxy
        if (strpos($className, '_IGNORE_') !== false) {
            return $className;
        }

        $visitor = new ProxyVisitor();
        return BaseProxy::newClassName($className, $visitor);
    }

    /**
     * Get real class name
     *
     * @param string $proxyClassName
     *
     * @return string
     */
    public static function getClassName(string $proxyClassName): string
    {
        [$className] = explode('_', $proxyClassName);
        return $className;
    }

    /**
     * Get original class name
     *
     * @param string $proxyClassName
     *
     * @return string
     */
    public static function getOriginalClassName(string $proxyClassName): string
    {
        $proxies = explode(ProxyVisitor::PROXY, $proxyClassName);

        return $proxies[0];
    }
}
