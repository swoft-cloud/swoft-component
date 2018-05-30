<?php

namespace Swoft\Bean\Collector;

use Swoft\Bean\Annotation\Date;
use Swoft\Bean\Annotation\Url;
use Swoft\Bean\Annotation\Regex;
use Swoft\Bean\Annotation\Alphabetic;
use Swoft\Bean\Annotation\Alphanumeric;
use Swoft\Bean\Annotation\Callback;
use Swoft\Bean\Annotation\CreditCard;
use Swoft\Bean\Annotation\Email;
use Swoft\Bean\Annotation\Enum;
use Swoft\Bean\Annotation\Floats;
use Swoft\Bean\Annotation\Integer;
use Swoft\Bean\Annotation\Ip;
use Swoft\Bean\Annotation\Number;
use Swoft\Bean\Annotation\Strings;
use Swoft\Bean\CollectorInterface;
use Swoft\Validator\AlphabeticValidator;
use Swoft\Validator\AlphanumericValidator;
use Swoft\Validator\CallbackValidator;
use Swoft\Validator\CreditCardValidator;
use Swoft\Validator\DateValidator;
use Swoft\Validator\EmailValidator;
use Swoft\Validator\EnumValidator;
use Swoft\Validator\FloatsValidator;
use Swoft\Validator\IntegerValidator;
use Swoft\Validator\IpValidator;
use Swoft\Validator\NumberValidator;
use Swoft\Validator\RegexValidator;
use Swoft\Validator\StringsValidator;
use Swoft\Validator\UrlValidator;

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
     *
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
            self::collectString($objectAnnotation, $className, $methodName);
        } elseif ($objectAnnotation instanceof Floats) {
            self::collectFloats($objectAnnotation, $className, $methodName);
        } elseif ($objectAnnotation instanceof Number) {
            self::collectNumber($objectAnnotation, $className, $methodName);
        } elseif ($objectAnnotation instanceof Integer) {
            self::collectInteger($objectAnnotation, $className, $methodName);
        } elseif ($objectAnnotation instanceof Enum) {
            self::collectEnum($objectAnnotation, $className, $methodName);
        }elseif ($objectAnnotation instanceof Url){
            self::collectUrl($objectAnnotation, $className, $methodName);
        }elseif ($objectAnnotation instanceof Ip){
            self::collectIp($objectAnnotation, $className, $methodName);
        }elseif ($objectAnnotation instanceof Regex){
            self::collectRegex($objectAnnotation, $className, $methodName);
        }elseif ($objectAnnotation instanceof Callback){
            self::collectCallback($objectAnnotation, $className, $methodName);
        }elseif ($objectAnnotation instanceof Alphabetic){
            self::collectAlphabetic($objectAnnotation, $className, $methodName);
        }elseif ($objectAnnotation instanceof Alphanumeric){
            self::collectAlphanumeric($objectAnnotation, $className, $methodName);
        }elseif ($objectAnnotation instanceof CreditCard){
            self::collectCreditCard($objectAnnotation, $className, $methodName);
        }elseif ($objectAnnotation instanceof Date){
            self::collectDate($objectAnnotation, $className, $methodName);
        }elseif ($objectAnnotation instanceof Email){
            self::collectEmail($objectAnnotation, $className, $methodName);
        }
    }

    /**
     * @return array
     */
    public static function getCollector(): array
    {
        return self::$validator;
    }

    /**
     * @param Strings $objectAnnotation
     * @param string  $className
     * @param string  $methodName
     */
    private static function collectString(Strings $objectAnnotation, string $className, string $methodName)
    {
        $from    = $objectAnnotation->getFrom();
        $name    = $objectAnnotation->getName();
        $min     = $objectAnnotation->getMin();
        $max     = $objectAnnotation->getMax();
        $default = $objectAnnotation->getDefault();
        $tpl     = $objectAnnotation->getTemplate();

        $params = [$min, $max, true, $tpl, $default];

        self::$validator[$className][$methodName]['validator'][$from][$name] = [
            'validator' => StringsValidator::class,
            'params'    => $params,
        ];
    }

    /**
     * @param Floats $objectAnnotation
     * @param string $className
     * @param string $methodName
     */
    private static function collectFloats(Floats $objectAnnotation, string $className, string $methodName)
    {
        $from    = $objectAnnotation->getFrom();
        $name    = $objectAnnotation->getName();
        $min     = $objectAnnotation->getMin();
        $max     = $objectAnnotation->getMax();
        $default = $objectAnnotation->getDefault();
        $tpl     = $objectAnnotation->getTemplate();

        $params = [$min, $max, true, $tpl, $default];

        self::$validator[$className][$methodName]['validator'][$from][$name] = [
            'validator' => FloatsValidator::class,
            'params'    => $params,
        ];
    }

    /**
     * @param \Swoft\Bean\Annotation\Number $objectAnnotation
     * @param string                        $className
     * @param string                        $methodName
     */
    private static function collectNumber(Number $objectAnnotation, string $className, string $methodName)
    {
        $from    = $objectAnnotation->getFrom();
        $name    = $objectAnnotation->getName();
        $min     = $objectAnnotation->getMin();
        $max     = $objectAnnotation->getMax();
        $default = $objectAnnotation->getDefault();
        $tpl     = $objectAnnotation->getTemplate();

        $params = [$min, $max, true, $tpl, $default];

        self::$validator[$className][$methodName]['validator'][$from][$name] = [
            'validator' => NumberValidator::class,
            'params'    => $params,
        ];
    }


    /**
     * @param \Swoft\Bean\Annotation\Integer $objectAnnotation
     * @param string                         $className
     * @param string                         $methodName
     */
    private static function collectInteger(Integer $objectAnnotation, string $className, string $methodName)
    {
        $from    = $objectAnnotation->getFrom();
        $name    = $objectAnnotation->getName();
        $min     = $objectAnnotation->getMin();
        $max     = $objectAnnotation->getMax();
        $default = $objectAnnotation->getDefault();
        $tpl     = $objectAnnotation->getTemplate();

        $params = [$min, $max, true, $tpl, $default];

        self::$validator[$className][$methodName]['validator'][$from][$name] = [
            'validator' => IntegerValidator::class,
            'params'    => $params,
        ];
    }

    /**
     * @param \Swoft\Bean\Annotation\Enum $objectAnnotation
     * @param string                      $className
     * @param string                      $methodName
     */
    private static function collectEnum(Enum $objectAnnotation, string $className, string $methodName)
    {
        $from    = $objectAnnotation->getFrom();
        $name    = $objectAnnotation->getName();
        $values  = $objectAnnotation->getValues();
        $default = $objectAnnotation->getDefault();
        $tpl     = $objectAnnotation->getTemplate();

        $params = [$values, true, $tpl, $default];

        self::$validator[$className][$methodName]['validator'][$from][$name] = [
            'validator' => EnumValidator::class,
            'params'    => $params,
        ];
    }

    /**
     * @param \Swoft\Bean\Annotation\Email $objectAnnotation
     * @param string $className
     * @param string $methodName
     */
    private static function collectEmail(Email $objectAnnotation, string $className, string $methodName)
    {
        $from = $objectAnnotation->getFrom();
        $name = $objectAnnotation->getName();
        $default = $objectAnnotation->getDefault();
        $tpl = $objectAnnotation->getTemplate();

        $params = [true, $tpl, $default];

        self::$validator[$className][$methodName]['validator'][$from][$name] = [
            'validator' => EmailValidator::class,
            'params' => $params,
        ];
    }

    /**
     * @param \Swoft\Bean\Annotation\CreditCard $objectAnnotation
     * @param string $className
     * @param string $methodName
     */
    private static function collectCreditCard(CreditCard $objectAnnotation, string $className, string $methodName)
    {
        $from = $objectAnnotation->getFrom();
        $name = $objectAnnotation->getName();
        $default = $objectAnnotation->getDefault();
        $tpl = $objectAnnotation->getTemplate();

        $params = [true, $tpl, $default];

        self::$validator[$className][$methodName]['validator'][$from][$name] = [
            'validator' => CreditCardValidator::class,
            'params' => $params,
        ];
    }

    /**
     * @param \Swoft\Bean\Annotation\Regex $objectAnnotation
     * @param string $className
     * @param string $methodName
     */
    private static function collectRegex(Regex $objectAnnotation, string $className, string $methodName)
    {
        $from = $objectAnnotation->getFrom();
        $name = $objectAnnotation->getName();
        $pattern = $objectAnnotation->getPattern();
        $default = $objectAnnotation->getDefault();
        $tpl = $objectAnnotation->getTemplate();

        $params = [$pattern, true, $tpl, $default];

        self::$validator[$className][$methodName]['validator'][$from][$name] = [
            'validator' => RegexValidator::class,
            'params' => $params,
        ];
    }

    /**
     * @param \Swoft\Bean\Annotation\Callback $objectAnnotation
     * @param string $className
     * @param string $methodName
     */
    private static function collectCallback(Callback $objectAnnotation, string $className, string $methodName)
    {
        $from = $objectAnnotation->getFrom();
        $name = $objectAnnotation->getName();
        $callback = $objectAnnotation->getCallback();
        $default = $objectAnnotation->getDefault();
        $tpl = $objectAnnotation->getTemplate();

        $params = [$callback, true, $tpl, $default];

        self::$validator[$className][$methodName]['validator'][$from][$name] = [
            'validator' => CallbackValidator::class,
            'params' => $params,
        ];
    }

    /**
     * @param \Swoft\Bean\Annotation\Date $objectAnnotation
     * @param string $className
     * @param string $methodName
     */
    private static function collectDate(Date $objectAnnotation, string $className, string $methodName)
    {
        $from = $objectAnnotation->getFrom();
        $name = $objectAnnotation->getName();
        $format = $objectAnnotation->getFormat();
        $default = $objectAnnotation->getDefault();
        $tpl = $objectAnnotation->getTemplate();

        $params = [$format, true, $tpl, $default];

        self::$validator[$className][$methodName]['validator'][$from][$name] = [
            'validator' => DateValidator::class,
            'params' => $params,
        ];
    }

    /**
     * @param \Swoft\Bean\Annotation\Alphabetic $objectAnnotation
     * @param string $className
     * @param string $methodName
     */
    private static function collectAlphabetic(Alphabetic $objectAnnotation, string $className, string $methodName)
    {
        $from = $objectAnnotation->getFrom();
        $name = $objectAnnotation->getName();
        $min = $objectAnnotation->getMin();
        $max = $objectAnnotation->getMax();
        $default = $objectAnnotation->getDefault();
        $tpl = $objectAnnotation->getTemplate();

        $params = [$min,$max, true, $tpl, $default];

        self::$validator[$className][$methodName]['validator'][$from][$name] = [
            'validator' => AlphabeticValidator::class,
            'params' => $params,
        ];
    }

    /**
     * @param \Swoft\Bean\Annotation\Alphanumeric $objectAnnotation
     * @param string $className
     * @param string $methodName
     */
    private static function collectAlphanumeric(Alphanumeric $objectAnnotation, string $className, string $methodName)
    {
        $from = $objectAnnotation->getFrom();
        $name = $objectAnnotation->getName();
        $min = $objectAnnotation->getMin();
        $max = $objectAnnotation->getMax();
        $default = $objectAnnotation->getDefault();
        $tpl = $objectAnnotation->getTemplate();

        $params = [$min,$max, true, $tpl, $default];

        self::$validator[$className][$methodName]['validator'][$from][$name] = [
            'validator' => AlphanumericValidator::class,
            'params' => $params,
        ];
    }

    /**
     * @param \Swoft\Bean\Annotation\Ip $objectAnnotation
     * @param string $className
     * @param string $methodName
     */
    private static function collectIp(Ip $objectAnnotation, string $className, string $methodName)
    {
        $from = $objectAnnotation->getFrom();
        $name = $objectAnnotation->getName();
        $default = $objectAnnotation->getDefault();
        $tpl = $objectAnnotation->getTemplate();

        $params = [true, $tpl, $default];

        self::$validator[$className][$methodName]['validator'][$from][$name] = [
            'validator' => IpValidator::class,
            'params' => $params,
        ];
    }

    /**
     * @param \Swoft\Bean\Annotation\Url $objectAnnotation
     * @param string $className
     * @param string $methodName
     */
    private static function collectUrl(Url $objectAnnotation, string $className, string $methodName)
    {
        $from = $objectAnnotation->getFrom();
        $name = $objectAnnotation->getName();
        $default = $objectAnnotation->getDefault();
        $tpl = $objectAnnotation->getTemplate();

        $params = [true, $tpl, $default];

        self::$validator[$className][$methodName]['validator'][$from][$name] = [
            'validator' => UrlValidator::class,
            'params' => $params,
        ];
    }
}