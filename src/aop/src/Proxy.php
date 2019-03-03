<?php

namespace Swoft\Aop;

use Swoft\Aop\Ast\Parser;
use Swoft\Aop\Ast\Visitor\ProxyVisitor;
use Swoft\Aop\Exception\AopException;

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
     * @throws AopException
     */
    public static function newClassName(string $className): string
    {
        $parser  = new Parser();
        $visitor = new ProxyVisitor();

        $parser->addNodeVisitor(ProxyVisitor::class, $visitor);
        $proxyCode = $parser->parse($className);
        $proxyName = $visitor->getProxyName();

        // Proxy file and proxy code
        $proxyFile = sprintf('%s/%s.php', sys_get_temp_dir(), $proxyName);
        $proxyCode = sprintf('<?php %s %s', PHP_EOL, $proxyCode);

        // Generate proxy class
        $result = file_put_contents($proxyFile, $proxyCode);
        if ($result === false) {
            throw new AopException(sprintf('Proxy file(%s) generate fail', $proxyFile));
        }

        // Load proxy php file
        require $proxyFile;

        // Remove proxy file
        unlink($proxyFile);

        // Proxy class
        $proxyClassName = $visitor->getProxyClassName();
        if (!class_exists($proxyClassName)) {
            throw new AopException(sprintf('Proxy class(%s) is not exist!', $proxyClassName));
        }

        return $proxyClassName;
    }
}
