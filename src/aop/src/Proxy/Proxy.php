<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Aop\Proxy;

use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard as StandardPrinter;
use PhpParser\PrettyPrinterAbstract;
use Swoft\Aop\Ast\ClassLoader;
use Swoft\Aop\Ast\Parser;
use Swoft\Aop\Ast\Visitors\ProxyVisitor;

/**
 * Class Proxy
 *
 * @author  huangzhhui <h@swoft.com>
 * @package Swoft\Aop\Proxy
 */
class Proxy
{
    /**
     * @var Parser
     */
    protected static $parser;

    /**
     * @var \PhpParser\PrettyPrinterAbstract
     */
    protected static $printer;

    /**
     * Return a proxy instance
     *
     * @param string $className
     * @return string
     * @throws \RuntimeException
     * @throws \ReflectionException
     */
    public static function newProxyClass(string $className): string
    {
        // Get or Create class AST
        ! self::hasParser() && self::initDefaultParser();
        $ast = self::getParser()->getOrParse($className);
        if (! $ast) {
            throw new \RuntimeException(sprintf('Class %s AST generate failure', $className));
        }
        // Generate proxy class
        $traverser = new NodeTraverser();
        $visitor = new ProxyVisitor($className, \uniqid('', false));
        $traverser->addVisitor($visitor);
        $proxyAst = $traverser->traverse($ast);
        if (! $proxyAst) {
            throw new \RuntimeException(sprintf('Class %s AST optimize failure', $className));
        }
        $proxyCode = self::getPrinter()->prettyPrint($proxyAst);

        // Load class
        eval($proxyCode);

        $proxyClassName = $visitor->getFullProxyClassName();
        unset($ast, $traverser, $visitor, $proxyAst, $proxyCode);
        return $proxyClassName;
    }

    /**
     * Note that CANNOT use async-io in swoole task process
     *
     * @param bool $useAsyncIO
     * @throws \RuntimeException
     */
    public static function initDefaultParser(bool $useAsyncIO = false)
    {
        self::setParser(new Parser(new ClassLoader(), (new ParserFactory())->create(ParserFactory::ONLY_PHP7), $useAsyncIO));
    }

    /**
     * @return bool
     */
    public static function hasParser(): bool
    {
        return self::$parser instanceof Parser;
    }

    /**
     * @param \Swoft\Aop\Ast\Parser $parser
     * @return Proxy
     */
    public static function setParser($parser)
    {
        self::$parser = $parser;
    }

    /**
     * @return Parser|null
     * @throws \RuntimeException
     */
    public static function getParser()
    {
        if (! self::$parser instanceof Parser) {
            self::initDefaultParser();
        }
        return self::$parser;
    }

    /**
     * @param \PhpParser\PrettyPrinterAbstract $printer
     * @return Proxy
     */
    public static function setPrinter($printer)
    {
        self::$printer = $printer;
    }

    /**
     * @return PrettyPrinterAbstract|null
     */
    public static function getPrinter()
    {
        if (! self::$printer instanceof PrettyPrinterAbstract) {
            self::$printer = new StandardPrinter();
        }
        return self::$printer;
    }
}
