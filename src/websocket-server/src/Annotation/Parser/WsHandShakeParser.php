<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2019-02-12
 * Time: 20:02
 */

namespace Swoft\WebSocket\Server\Annotation\Parser;

use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Annotation\AnnotationException;
use Swoft\WebSocket\Server\Annotation\Mapping\WsHandShake;

/**
 * Class WsHandShakeParser
 * @since 2.0
 * @AnnotationParser(WsHandShake::class)
 */
class WsHandShakeParser extends Parser
{
    /**
     * Parse object
     *
     * @param int    $type Class or Method or Property
     * @param WsHandShake $annotation Annotation object
     *
     * @return array
     * Return empty array is nothing to do!
     * When class type return [$beanName, $className, $scope, $alias, $size] is to inject bean
     * When property type return [$propertyValue, $isRef] is to reference value
     */
    public function parse(int $type, $annotation): array
    {
        if ($type !== self::TYPE_METHOD) {
            throw new AnnotationException('`@WsHandShake` must be defined on class method!');
        }

        WsModuleParser::addMethodBind($this->className, $this->methodName);

        return [];
    }
}
