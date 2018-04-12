<?php

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

/**
 * Class ValidatorCollector
 *
 * @package Swoft\Bean\Collector
 */
class ValidatorCollector implements CollectorInterface
{
    /**
     * @var array
     */
    private static $validator = [];

    /**
     * @param string $className
     * @param object $objectAnnotation
     * @param string $propertyName
     * @param string $methodName
     * @param null   $propertyValue
     * @return mixed|void
     */
    public static function collect(
        string $className,
        $objectAnnotation = null,
        string $propertyName = '',
        string $methodName = '',
        $propertyValue = null
    ) {
        if ($objectAnnotation instanceof Strings) {
            $from = $objectAnnotation->getFrom();
            $name = $objectAnnotation->getName();
            $min = $objectAnnotation->getMin();
            $max = $objectAnnotation->getMax();
            $default = $objectAnnotation->getDefault();

            $params = [$min, $max, $default];
            self::$validator[$className][$methodName]['validator'][$from][$name] = [
                'validator' => StringsValidator::class,
                'params'    => $params,
            ];

            return;
        }

        if ($objectAnnotation instanceof Floats) {
            $from = $objectAnnotation->getFrom();
            $name = $objectAnnotation->getName();
            $min = $objectAnnotation->getMin();
            $max = $objectAnnotation->getMax();
            $default = $objectAnnotation->getDefault();

            $params = [$min, $max, $default];
            self::$validator[$className][$methodName]['validator'][$from][$name] = [
                'validator' => FloatsValidator::class,
                'params'    => $params,
            ];
            return;
        }
        if ($objectAnnotation instanceof Number) {
            $from = $objectAnnotation->getFrom();
            $name = $objectAnnotation->getName();
            $min = $objectAnnotation->getMin();
            $max = $objectAnnotation->getMax();
            $default = $objectAnnotation->getDefault();

            $params = [$min, $max, $default];

            self::$validator[$className][$methodName]['validator'][$from][$name] = [
                'validator' => NumberValidator::class,
                'params'    => $params,
            ];
            return;
        }

        if ($objectAnnotation instanceof Integer) {
            $from = $objectAnnotation->getFrom();
            $name = $objectAnnotation->getName();
            $min = $objectAnnotation->getMin();
            $max = $objectAnnotation->getMax();
            $default = $objectAnnotation->getDefault();

            $params = [$min, $max, $default];
            self::$validator[$className][$methodName]['validator'][$from][$name] = [
                'validator' => IntegerValidator::class,
                'params'    => $params,
            ];
            return;
        }

        if ($objectAnnotation instanceof Enum) {
            $from = $objectAnnotation->getFrom();
            $name = $objectAnnotation->getName();
            $values = $objectAnnotation->getValues();
            $default = $objectAnnotation->getDefault();

            $params = [$values, $default];
            self::$validator[$className][$methodName]['validator'][$from][$name] = [
                'validator' => EnumValidator::class,
                'params'    => $params,
            ];
            return;
        }
    }

    /**
     * @return array
     */
    public static function getCollector(): array
    {
        return self::$validator;
    }
}