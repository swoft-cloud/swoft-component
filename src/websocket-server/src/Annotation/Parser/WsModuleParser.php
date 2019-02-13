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
 * @AnnotationParser(WebSocket::class)
 */
class WsModuleParser extends Parser
{
    /**
     * @var array
     */
    private static $routes = [];

    /**
     * Parse object
     *
     * @param int       $type Class or Method or Property
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
            throw new AnnotationException('`@WebSocket` must be defined on class!');
        }

        $class = $this->className;
        $path  = $annotation->getPath();

        self::$routes[$class] = [
            'path'           => $path,
            'handler'        => $this->className,
            'defaultCommand' => $annotation->getDefaultCommand(),
            'messageParser'  => $annotation->getMessageParser(),
        ];

        return [$class, $class, Bean::SINGLETON, ''];
    }

    public static function registerTo(Router $router): void
    {
        // $router->add($path, $handler);
    }

    public static function addMethodBind(string $class, string $method, array $option = []): void
    {
        self::$routes[$class] = \array_merge(self::$routes[$class], [

        ]);
    }
}
