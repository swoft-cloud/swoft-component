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
use Swoft\Tcp\Server\Annotation\Mapping\TcpMapping;
use Swoft\Tcp\Server\Router\RouteRegister;

/**
 * Class TcpMappingParser
 *
 * @since 2.0.3
 * @AnnotationParser(TcpMapping::class)
 */
class TcpMappingParser extends Parser
{
    /**
     * Parse object
     *
     * @param int        $type       Class or Method or Property
     * @param TcpMapping $annotation Annotation object
     *
     * @return array
     * Return empty array is nothing to do!
     * When class type return [$beanName, $className, $scope, $alias, $size] is to inject bean
     * When property type return [$propertyValue, $isRef] is to reference value
     * @throws AnnotationException
     */
    public function parse(int $type, $annotation): array
    {
        if ($type !== self::TYPE_METHOD) {
            throw new AnnotationException('`@TcpMapping` must be defined on class method!');
        }

        RouteRegister::bindCommand($this->className, $this->methodName, [
            'route'   => $annotation->getRoute() ?: $this->methodName,
            'root'    => $annotation->isRoot(),
            'middles' => $annotation->getMiddlewares(),
        ]);

        return [];
    }
}
