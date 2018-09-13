<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Bean\Collector;

use Swoft\Bean\Annotation\Enum;
use Swoft\Bean\Annotation\Floats;
use Swoft\Bean\Annotation\Integer;
use Swoft\Bean\Annotation\Number;
use Swoft\Bean\Annotation\Strings;
use Swoft\Bean\CollectorInterface;
use Swoft\Validator\EnumValidator;
use Swoft\Validator\FloatsValidator;
use Swoft\Validator\IntegerValidator;
use Swoft\Validator\NumberValidator;
use Swoft\Validator\StringsValidator;

class ValidatorCollector implements CollectorInterface
{
    private static $validator = [];

    public static function collect(
        string $className,
        $objectAnnotation = null,
        string $propertyName = '',
        string $methodName = '',
        $propertyValue = null
    ) {
        if ($objectAnnotation instanceof Strings) {
            self::collectString($objectAnnotation, $className, $methodName);
        } elseif ($objectAnnotation instanceof Floats) {
            self::collectFloats($objectAnnotation, $className, $methodName);
        } elseif ($objectAnnotation instanceof Number) {
            self::collectNumber($objectAnnotation, $className, $methodName);
        } elseif ($objectAnnotation instanceof Integer) {
            self::collectInteger($objectAnnotation, $className, $methodName);
        } elseif ($objectAnnotation instanceof Enum) {
            self::collectEnum($objectAnnotation, $className, $methodName);
        }
    }

    public static function getCollector(): array
    {
        return self::$validator;
    }

    private static function collectString(Strings $objectAnnotation, string $className, string $methodName)
    {
        $from = $objectAnnotation->getFrom();
        $name = $objectAnnotation->getName();
        $min = $objectAnnotation->getMin();
        $max = $objectAnnotation->getMax();
        $default = $objectAnnotation->getDefault();
        $tpl = $objectAnnotation->getTemplate();

        $params = [$min, $max, true, $tpl, $default];

        self::$validator[$className][$methodName]['validator'][$from][$name] = [
            'validator' => StringsValidator::class,
            'params' => $params,
        ];
    }

    private static function collectFloats(Floats $objectAnnotation, string $className, string $methodName)
    {
        $from = $objectAnnotation->getFrom();
        $name = $objectAnnotation->getName();
        $min = $objectAnnotation->getMin();
        $max = $objectAnnotation->getMax();
        $default = $objectAnnotation->getDefault();
        $tpl = $objectAnnotation->getTemplate();

        $params = [$min, $max, true, $tpl, $default];

        self::$validator[$className][$methodName]['validator'][$from][$name] = [
            'validator' => FloatsValidator::class,
            'params' => $params,
        ];
    }

    private static function collectNumber(Number $objectAnnotation, string $className, string $methodName)
    {
        $from = $objectAnnotation->getFrom();
        $name = $objectAnnotation->getName();
        $min = $objectAnnotation->getMin();
        $max = $objectAnnotation->getMax();
        $default = $objectAnnotation->getDefault();
        $tpl = $objectAnnotation->getTemplate();

        $params = [$min, $max, true, $tpl, $default];

        self::$validator[$className][$methodName]['validator'][$from][$name] = [
            'validator' => NumberValidator::class,
            'params' => $params,
        ];
    }

    private static function collectInteger(Integer $objectAnnotation, string $className, string $methodName)
    {
        $from = $objectAnnotation->getFrom();
        $name = $objectAnnotation->getName();
        $min = $objectAnnotation->getMin();
        $max = $objectAnnotation->getMax();
        $default = $objectAnnotation->getDefault();
        $tpl = $objectAnnotation->getTemplate();

        $params = [$min, $max, true, $tpl, $default];

        self::$validator[$className][$methodName]['validator'][$from][$name] = [
            'validator' => IntegerValidator::class,
            'params' => $params,
        ];
    }

    private static function collectEnum(Enum $objectAnnotation, string $className, string $methodName)
    {
        $from = $objectAnnotation->getFrom();
        $name = $objectAnnotation->getName();
        $values = $objectAnnotation->getValues();
        $default = $objectAnnotation->getDefault();
        $tpl = $objectAnnotation->getTemplate();

        $params = [$values, true, $tpl, $default];

        self::$validator[$className][$methodName]['validator'][$from][$name] = [
            'validator' => EnumValidator::class,
            'params' => $params,
        ];
    }
}
