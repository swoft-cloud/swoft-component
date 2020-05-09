<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Console\Annotation\Parser;

use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Swoft\Annotation\Exception\AnnotationException;
use Swoft\Console\Annotation\Mapping\CommandArgument;
use Swoft\Console\CommandRegister;
use Swoft\Console\FlagType;
use Toolkit\Cli\Flags;

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
     * @param int             $type       Class or Method or Property
     * @param CommandArgument $annotation Annotation object
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
            throw new AnnotationException('`@CommandArgument` must be defined on class method!');
        }

        $method  = $this->methodName;
        $valType = $annotation->getType();
        $defVal  = $annotation->getDefault();

        CommandRegister::bindArgument($this->className, $method, $annotation->getName(), [
            'method'  => $method,
            'name'    => $annotation->getName(),
            'desc'    => $annotation->getDesc(),
            'mode'    => $annotation->getMode(),
            'type'    => $valType,
            'default' => $valType === FlagType::BOOL ? Flags::filterBool($defVal) : $defVal,
        ]);

        return [];
    }
}
