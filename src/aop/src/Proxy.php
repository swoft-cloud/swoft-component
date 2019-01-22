<?php

namespace Swoft\Aop;

use Swoft\Aop\Ast\Parser;
use Swoft\Aop\Ast\Visitor\ProxyVisitor;

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
     * @throws Exception\AopException
     */
    public static function newClassName(string $className): string
    {
        $parser  = new Parser();
        $visitor = new ProxyVisitor();

        $parser->addNodeVisitor(ProxyVisitor::class, $visitor);
        $proxyCode = $parser->parse($className);

        eval($proxyCode);

        $proxyClassName = $visitor->getProxyClassName();

        return $proxyClassName;
    }
}