<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\Annotation\Parser;

use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Annotation\AnnotationException;
use Swoft\Server\Swoole\SwooleEvent;
use Swoft\WebSocket\Server\Annotation\Mapping\OnHandShake;

/**
 * Class WsHandShakeParser
 * @since 2.0
 * @AnnotationParser(OnHandShake::class)
 */
class OnHandShakeParser extends Parser
{
    /**
     * Parse object
     *
     * @param int    $type Class or Method or Property
     * @param OnHandShake $annotation Annotation object
     *
     * @return array
     * Return empty array is nothing to do!
     * When class type return [$beanName, $className, $scope, $alias, $size] is to inject bean
     * When property type return [$propertyValue, $isRef] is to reference value
     */
    public function parse(int $type, $annotation): array
    {
        if ($type !== self::TYPE_METHOD) {
            throw new AnnotationException('`@OnHandShake` must be defined on class method!');
        }

        WsModuleParser::bindEvent($this->className, $this->methodName, SwooleEvent::HANDSHAKE);

        return [];
    }
}
