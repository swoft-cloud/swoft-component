<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\WebSocket\Server\Annotation\Parser;

use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Annotation\Exception\AnnotationException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\WebSocket\Server\Annotation\Mapping\WsController;
use Swoft\WebSocket\Server\Router\RouteRegister;

/**
 * Class WebSocketParser
 *
 * @since 2.0
 *
 * @AnnotationParser(WsController::class)
 */
class WsControllerParser extends Parser
{
    /**
     * Parse object
     *
     * @param int          $type Class or Method or Property
     * @param WsController $ann  Annotation object
     *
     * @return array
     * Return empty array is nothing to do!
     * When class type return [$beanName, $className, $scope, $alias, $size] is to inject bean
     * When property type return [$propertyValue, $isRef] is to reference value
     * @throws AnnotationException
     */
    public function parse(int $type, $ann): array
    {
        if ($type !== self::TYPE_CLASS) {
            throw new AnnotationException('`@WsController` must be defined on class!');
        }

        $class = $this->className;

        RouteRegister::bindController($class, $ann->getPrefix(), $ann->getMiddlewares());

        return [$class, $class, Bean::SINGLETON, ''];
    }
}
