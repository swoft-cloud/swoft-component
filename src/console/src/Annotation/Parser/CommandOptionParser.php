<?php

namespace Swoft\Console\Bean\Parser;

use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Annotation\AnnotationException;
use Swoft\Console\Annotation\Mapping\CommandOption;

/**
 * Class CommandOptionParser
 * @since 2.0
 *
 * @AnnotationParser(CommandOption::class)
 */
class CommandOptionParser extends Parser
{
    /**
     * Parse object
     *
     * @param int           $type Class or Method or Property
     * @param CommandOption $annotation Annotation object
     *
     * @return array
     * Return empty array is nothing to do!
     * When class type return [$beanName, $className, $scope, $alias, $size] is to inject bean
     * When property type return [$propertyValue, $isRef] is to reference value
     */
    public function parse(int $type, $annotation): array
    {
        if ($type === self::TYPE_PROPERTY) {
            throw new AnnotationException('`@CommandOption` must be defined on class or method!');
        }

        // add route info for controller action
        CommandParser::bindOption($this->className, $this->methodName, $annotation->getName(), [
            'method'  => $this->methodName,
            'name'    => $annotation->getName(),
            'short'   => $annotation->getShort(),
            'desc'    => $annotation->getDesc(),
            'mode'    => $annotation->getMode(),
            'type'    => $annotation->getType(),
            'default' => $annotation->getDefault(),
        ]);

        return [];
    }
}
