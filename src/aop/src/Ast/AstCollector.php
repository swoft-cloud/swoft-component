<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Aop\Ast;

/**
 * Class AstCollector
 *
 * @author  huangzhhui <h@swoft.com>
 * @package Swoft\Aop\Ast
 */
class AstCollector
{
    /**
     * @var array
     */
    protected static $container = [];

    /**
     * @return void
     */
    public static function clear()
    {
        self::$container = [];
    }

    /**
     * @param string $class
     * @return bool
     */
    public static function has(string $class): bool
    {
        return isset(self::$container[$class]);
    }

    /**
     * @param string $class
     * @return mixed
     */
    public static function get(string $class)
    {
        return self::$container[$class];
    }

    /**
     * @param string $class
     * @param array  $ast
     */
    public static function set(string $class, array $ast)
    {
        self::$container[$class] = $ast;
    }

    /**
     * @param string $class
     */
    public static function remove(string $class)
    {
        unset(self::$container[$class]);
    }

    /**
     * @return array
     */
    public static function getContainer(): array
    {
        return self::$container;
    }

    /**
     * @param array $container
     * @return AstCollector
     */
    public static function setContainer($container)
    {
        self::$container = $container;
    }
}
