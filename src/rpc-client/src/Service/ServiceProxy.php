<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Rpc\Client\Service;

use PhpParser\NodeTraverser;
use Swoft\Aop\Proxy\Proxy;
use Swoft\App;
use Swoft\Rpc\Client\Service;

/**
 * Class ServiceProxy
 *
 * @author  huangzhhui <h@swoft.com>
 * @package Swoft\Rpc\Client\Service
 */
class ServiceProxy extends Proxy
{
    /**
     * @param string $className
     * @param string $interfaceClass
     * @throws \RuntimeException
     * @throws \ReflectionException
     */
    public static function loadProxyClass(string $className, string $interfaceClass)
    {
        // Init Parser Context
        ! Proxy::hasParser() && Proxy::initDefaultParser(App::isWorkerStatus());

        // Create interface AST
        $interfaceAst = self::getParser()->parse($interfaceClass);
        if (! $interfaceAst) {
            throw new \RuntimeException(sprintf('Interface %s AST generate failure', $className));
        }

        // Create proxy class
        $traverser = new NodeTraverser();
        $traverser->addVisitor(new Service\Vistors\ServiceVistor($className));
        $classAst = $traverser->traverse($interfaceAst);
        if (! $classAst) {
            throw new \RuntimeException(sprintf('Class %s AST optimize failure', $className));
        }

        // Create code of proxy class by AST
        $code = self::getPrinter()->prettyPrint($classAst);

        // Load class
        eval($code);

        // Generate Class AST
        self::getParser()->getOrParse($className, '<?php ' . $code);
    }
}
