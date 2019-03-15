<?php

namespace Swoft\Console\Annotation\Parser;

use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Annotation\AnnotationException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Console\Annotation\Mapping\CommandHandler;
use Swoft\Console\CommandRegister;
use Swoft\Stdlib\Helper\Str;

/**
 * Class CommandHandlerParser
 *
 * @since 2.0
 * @AnnotationParser(CommandHandler::class)
 */
class CommandHandlerParser extends Parser
{
    /**
     * Parse object
     *
     * @param int            $type Class or Method or Property
     * @param CommandHandler $annotation Annotation object
     *
     * @return array
     * Return empty array is nothing to do!
     * When class type return [$beanName, $className, $scope, $alias, $size] is to inject bean
     * When property type return [$propertyValue, $isRef] is to reference value
     */
    public function parse(int $type, $annotation): array
    {
        if ($type !== self::TYPE_CLASS) {
            throw new AnnotationException('`@CommandMapping` must be defined on class!');
        }

        $class  = $this->className;
        $method = $annotation->getMethod();

        // add route info for controller action
        CommandRegister::addHandler($class, $method, [
            'command' => $annotation->getName() ?: Str::getClassName($class, 'Handler'),
            'method'  => $method,
            'alias'   => $annotation->getAlias(),
            'aliases' => $annotation->getAliases(),
            'desc'    => $annotation->getDesc(),
            'usage'   => $annotation->getUsage(),
        ]);

        return [$class, $class, Bean::SINGLETON, ''];
    }
}
