<?php declare(strict_types=1);


namespace Swoft\Rpc\Client;


use Swoft\Proxy\Proxy as BaseProxy;
use Swoft\Rpc\Client\Exception\RpcClientException;
use Swoft\Rpc\Client\Proxy\Ast\ProxyVisitor;

class Proxy
{
    /**
     * @param string $className
     *
     * @return string
     * @throws RpcClientException
     * @throws \Swoft\Proxy\Exception\ProxyException
     */
    public static function newClassName(string $className): string
    {
        if (!interface_exists($className)) {
            throw new RpcClientException(
                sprintf('`@var` for `@Reference` must be exist interface!')
            );
        }

        $proxyId   = sprintf('IGNORE_%s', uniqid());
        $visitor   = new ProxyVisitor($proxyId);
        $className = BaseProxy::newClassName($className, $visitor);
        return $className;
    }
}