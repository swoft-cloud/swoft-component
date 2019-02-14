<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2019-02-04
 * Time: 16:46
 */

namespace Swoft\WebSocket\Server\Annotation\Parser;

use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Annotation\AnnotationException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\WebSocket\Server\Annotation\Mapping\WsModule;
use Swoft\WebSocket\Server\Router\Router;

/**
 * Class WebSocketParser
 * @since 2.0
 *
 * @AnnotationParser(WsModule::class)
 */
class WsModuleParser extends Parser
{
    /**
     * @var array
     */
    private static $modules = [];

    /**
     * Parse object
     *
     * @param int      $type Class or Method or Property
     * @param WsModule $annotation Annotation object
     *
     * @return array
     * Return empty array is nothing to do!
     * When class type return [$beanName, $className, $scope, $alias, $size] is to inject bean
     * When property type return [$propertyValue, $isRef] is to reference value
     */
    public function parse(int $type, $annotation): array
    {
        if ($type !== self::TYPE_CLASS) {
            throw new AnnotationException('`@WsModule` must be defined on class!');
        }

        $class  = $this->className;
        $option = [
            'path'           => $annotation->getPath(),
            'name'           => $annotation->getName(),
            'class'          => $class,
            'defaultCommand' => $annotation->getDefaultCommand(),
            'messageParser'  => $annotation->getMessageParser(),
        ];

        if (isset(self::$modules[$class])) {
            self::$modules[$class] = \array_merge(self::$modules[$class], $option);
        } else {
            self::$modules[$class] = $option;
        }

        return [$class, $class, Bean::SINGLETON, ''];
    }

    public static function registerTo(Router $router): void
    {
        // $router->add($path, $handler);
    }

    /**
     * @param string $moduleClass
     * @param string $method
     * @param string $event such as: message, handShake, open, close
     */
    public static function bindEvent(string $moduleClass, string $method, string $event): void
    {
        self::$modules[$moduleClass][$event] = $method;
    }

    public static function bindController(string $moduleClass, string $controllerClass, string $prefix): void
    {
        self::$modules[$moduleClass]['routes'][] = [];
    }

    public static function bindCommand(): void
    {

    }
}
