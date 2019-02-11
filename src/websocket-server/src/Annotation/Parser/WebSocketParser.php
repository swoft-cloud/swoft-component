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
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\WebSocket\Server\Annotation\Mapping\WebSocket;
use Swoft\WebSocket\Server\Router\Router;

/**
 * Class WebSocketParser
 * @since 2.0
 * @package Swoft\WebSocket\Server\Annotation\Parser
 *
 * @AnnotationParser(WebSocket::class)
 */
class WebSocketParser extends Parser
{
    /**
     * @var array
     */
    private static $routes = [];

    /**
     * Parse object
     *
     * @param int       $type Class or Method or Property
     * @param WebSocket $annotation Annotation object
     *
     * @return array
     * Return empty array is nothing to do!
     * When class type return [$beanName, $className, $scope, $alias, $size] is to inject bean
     * When property type return [$propertyValue, $isRef] is to reference value
     */
    public function parse(int $type, $annotation): array
    {
        $path = $annotation->getPath();

        self::$routes[$path] = [
            'path'    => $path,
            'handler' => $this->className,
        ];

        return [$this->className, $this->className, Bean::SINGLETON, ''];
    }

    public static function registerTo(Router $router): void
    {

    }
}
