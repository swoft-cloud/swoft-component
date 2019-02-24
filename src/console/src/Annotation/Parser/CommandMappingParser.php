<?php

namespace Swoft\Console\Annotation\Parser;

use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Annotation\AnnotationException;
use Swoft\Console\Annotation\Mapping\CommandMapping;

/**
 * Class CommandMappingParser
 *
 * @since 2.0
 * @AnnotationParser(CommandMapping::class)
 */
class CommandMappingParser extends Parser
{
    /**
     * Parse object
     *
     * @param int            $type Class or Method or Property
     * @param CommandMapping $annotation Annotation object
     *
     * @return array
     * Return empty array is nothing to do!
     * When class type return [$beanName, $className, $scope, $alias, $size] is to inject bean
     * When property type return [$propertyValue, $isRef] is to reference value
     */
    public function parse(int $type, $annotation): array
    {
        if ($type !== self::TYPE_METHOD) {
            throw new AnnotationException('`@CommandMapping` must be defined on class method!');
        }

        $method = $this->methodName;

        // add route info for controller action
        CommandParser::addRoute($this->className, $method, [
            'command' => $annotation->getName() ?: $method,
            'method'  => $method,
            // 'alias'   => $annotation->getAlias(),
            'aliases' => $annotation->getAliases(),
            'desc'    => $annotation->getDesc(),
        ]);

        return [];
    }
}
