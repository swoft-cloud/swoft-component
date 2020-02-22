<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Tcp\Server\Annotation\Parser;

use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Annotation\Exception\AnnotationException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Tcp\Server\Annotation\Mapping\TcpController;
use Swoft\Tcp\Server\Router\RouteRegister;

/**
 * Class TcpControllerParser
 *
 * @since 2.0.3
 * @AnnotationParser(TcpController::class)
 */
final class TcpControllerParser extends Parser
{
    /**
     * Parse object
     *
     * @param int           $type       Class or Method or Property
     * @param TcpController $annotation Annotation object
     *
     * @return array
     * Return empty array is nothing to do!
     * When class type return [$beanName, $className, $scope, $alias, $size] is to inject bean
     * When property type return [$propertyValue, $isRef] is to reference value
     * @throws AnnotationException
     */
    public function parse(int $type, $annotation): array
    {
        if ($type !== self::TYPE_CLASS) {
            throw new AnnotationException('`@TcpController` must be defined on class!');
        }

        $class  = $this->className;
        $prefix = $annotation->getPrefix();

        RouteRegister::bindController($class, $prefix, $annotation->getMiddlewares());

        return [$class, $class, Bean::SINGLETON, ''];
    }
}
