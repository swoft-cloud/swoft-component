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
use Swoft\Console\Annotation\Mapping\CommandOption;
use Swoft\Console\CommandRegister;
use Swoft\Console\FlagType;
use Toolkit\Cli\Flags;

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
     * @param int           $type   Class or Method or Property
     * @param CommandOption $option Annotation object
     *
     * @return array
     * Return empty array is nothing to do!
     * When class type return [$beanName, $className, $scope, $alias, $size] is to inject bean
     * When property type return [$propertyValue, $isRef] is to reference value
     * @throws AnnotationException
     */
    public function parse(int $type, $option): array
    {
        if ($type === self::TYPE_PROPERTY) {
            throw new AnnotationException('`@CommandOption` must be defined on class or method!');
        }

        $method  = $this->methodName;
        $defVal  = $option->getDefault();
        $valType = $option->getType();

        // if ($valType === 'BOOL') {
        //     $defVal = Flags::filterBool($defVal);
        // }

        // Add route info for group command action
        CommandRegister::bindOption($this->className, $method, $option->getName(), [
            'method'  => $method,
            'name'    => $option->getName(),
            'short'   => $option->getShort(),
            'desc'    => $option->getDesc(),
            'mode'    => $option->getMode(),
            'type'    => $valType,
            'default' => $valType === FlagType::BOOL ? Flags::filterBool($defVal) : $defVal,
        ]);

        return [];
    }
}
