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
use Swoft\WebSocket\Server\Annotation\Mapping\MessageMapping;
use Swoft\WebSocket\Server\Router\RouteRegister;

/**
 * Class MessageMappingParser
 *
 * @since 2.0
 * @AnnotationParser(MessageMapping::class)
 */
class MessageMappingParser extends Parser
{
    /**
     * Parse object
     *
     * @param int            $type Class or Method or Property
     * @param MessageMapping $ann  Annotation object
     *
     * @return array
     * Return empty array is nothing to do!
     * When class type return [$beanName, $className, $scope, $alias, $size] is to inject bean
     * When property type return [$propertyValue, $isRef] is to reference value
     * @throws AnnotationException
     */
    public function parse(int $type, $ann): array
    {
        if ($type !== self::TYPE_METHOD) {
            throw new AnnotationException('`@MessageMapping` must be defined on class method!');
        }

        RouteRegister::bindCommand($this->className, $this->methodName, $ann->getCommand(), [
            'isRoot'  => $ann->isRoot(),
            'opcode'  => $ann->getOpcode(),
            'middles' => $ann->getMiddlewares(),
        ]);

        return [];
    }
}
