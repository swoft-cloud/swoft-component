<?php

namespace Swoft\Console\Annotation\Parser;

use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Annotation\AnnotationException;
use Swoft\Console\Annotation\Mapping\CommandArgument;

/**
 * Class CommandArgumentParser
 * @since 2.0
 *
 * @AnnotationParser(CommandArgument::class)
 */
class CommandArgumentParser extends Parser
{
    /**
     * Parse object
     *
     * @param int             $type Class or Method or Property
     * @param CommandArgument $annotation Annotation object
     *
     * @return array
     * Return empty array is nothing to do!
     * When class type return [$beanName, $className, $scope, $alias, $size] is to inject bean
     * When property type return [$propertyValue, $isRef] is to reference value
     */
    public function parse(int $type, $annotation): array
    {
        if ($type !== self::TYPE_METHOD) {
            throw new AnnotationException('`@CommandArgument` must be defined on class method!');
        }

        // add route info for controller action
        CommandParser::bindArgument($this->className, $this->methodName, $annotation->getName(), [
            'method'  => $this->methodName,
            'name'    => $annotation->getName(),
            'desc'    => $annotation->getDesc(),
            'mode'    => $annotation->getMode(),
            'type'    => $annotation->getType(),
            'default' => $annotation->getDefault(),
        ]);

        return [];
    }
}
