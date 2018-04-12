<?php

namespace Swoft\Helper;

use Swoft\Exception\ValidatorException;

/**
 * Class ValidatorHelper
 *
 * @package Swoft\Helper
 */
class ValidatorHelper
{
    /**
     * number pattern
     *
     * @var string
     */
    private static $numberPattern = '/^\s*[+]?\d+\s*$/';

    /**
     * integer pattern
     *
     * @var string
     */
    private static $integerPattern = '/^\s*[+-]?\d+\s*$/';

    /**
     * float pattern
     *
     * @var string
     */
    private static $floatPattern = '/^(-?\d+)(\.\d+)+$/';

    /**
     * Validate integer
     *
     * @param string   $name   Parameter name
     * @param mixed    $value  Parameter value
     * @param int|null $min    Parameter minimun value
     * @param int|null $max    Parameter maximum value
     * @param bool     $throws Determine if throw an ValidatorException when invalid
     *
     * @return mixed
     * @throws \Swoft\Exception\ValidatorException
     */
    public static function validateInteger(string $name, $value, $min = null, $max = null, bool $throws = true)
    {
        if ($value === null) {
            return self::validateError(sprintf('Parameter %s must be passed', $name), $throws);
        }

        if (!preg_match(self::$integerPattern, (string)$value)) {
            return self::validateError(sprintf('Parameter %s is not integer type', $name), $throws);
        }

        $value = (int)$value;
        if ($min !== null && $value < $min) {
            return self::validateError(sprintf('Parameter %s is too small (minimum is %d)', $name, $min), $throws);
        }

        if ($max !== null && $value > $max) {
            return self::validateError(sprintf('Parameter %s is too big (maximum is %d)', $name, $max), $throws);
        }

        return (int)$value;
    }

    /**
     * Validate number
     *
     * @param string   $name   Parameter name
     * @param mixed    $value  Parameter value
     * @param int|null $min    Parameter minimun value
     * @param int|null $max    Parameter maximum value
     * @param bool     $throws Determine if throw an ValidatorException when invalid
     *
     * @return mixed
     * @throws \Swoft\Exception\ValidatorException
     */
    public static function validateNumber(string $name, $value, $min = null, $max = null, bool $throws = true)
    {
        if ($value === null) {
            return self::validateError(sprintf('Parameter %s must be passed', $name), $throws);
        }

        if (!preg_match(self::$numberPattern, (string)$value)) {
            return self::validateError(sprintf('Parameter %s is not a number', $name), $throws);
        }

        $value = (int)$value;
        if ($min !== null && $value < $min) {
            return self::validateError(sprintf('Parameter %s is too small (minimum is %d)', $name, $min), $throws);
        }

        if ($max !== null && $value > $max) {
            return self::validateError(sprintf('Parameter %s is too big (maximum is %d)', $name, $max), $throws);
        }

        return (int)$value;
    }

    /**
     * Validate float
     *
     * @param string     $name   Parameter name
     * @param mixed      $value  Parameter value
     * @param float|null $min    Parameter minimun value
     * @param float|null $max    Parameter maximum value
     * @param bool       $throws Determine if throw an ValidatorException when invalid
     *
     * @throws ValidatorException
     * @return mixed
     */
    public static function validateFloat(
        string $name,
        $value,
        float $min = null,
        float $max = null,
        bool $throws = true
    ) {
        if ($value === null) {
            return self::validateError(sprintf('Parameter %s must be passed', $name), $throws);
        }

        if (!preg_match(self::$floatPattern, (string)$value)) {
            return self::validateError(sprintf('Parameter %s is not float type', $name), $throws);
        }

        $value = (float)$value;
        if ($min !== null && $value < $min) {
            return self::validateError(sprintf('Parameter %s is too small (minimum is %d)', $name, $min), $throws);
        }

        if ($max !== null && $value > $max) {
            return self::validateError(sprintf('Parameter %s is too big (maximum is %d)', $name, $max), $throws);
        }

        return (float)$value;
    }

    /**
     * Validate string
     *
     * @param string   $name   Parameter name
     * @param mixed    $value  Parameter value
     * @param int|null $min    Parameter length minimun value
     * @param int|null $max    Parameter length maximum value
     * @param bool     $throws Determine if throw an ValidatorException when invalid
     *
     * @return mixed
     * @throws \Swoft\Exception\ValidatorException
     */
    public static function validateString(string $name, $value, int $min = null, int $max = null, bool $throws = true)
    {
        if ($value === null) {
            return self::validateError(sprintf('Parameter %s must be passed', $name), $throws);
        }

        if (!\is_string($value)) {
            return self::validateError(sprintf('Parameter %s is not string type', $name), $throws);
        }
        $length = mb_strlen($value);
        if ($min !== null && $length < $min) {
            return self::validateError(sprintf('Parameter %s length is too short (minimum is %d)', $name, $min), $throws);
        }

        if ($max !== null && $length > $max) {
            return self::validateError(sprintf('Parameter %s length is too long (maximum is %d)', $name, $max), $throws);
        }

        return (string)$value;
    }

    /**
     * Validate enum
     *
     * @param string $name        Parameter name
     * @param mixed  $value       Parameter value
     * @param array  $validValues Enum values
     * @param bool   $throws      Determine if throw an ValidatorException when invalid
     *
     * @return mixed
     * @throws \Swoft\Exception\ValidatorException
     */
    public static function validateEnum(string $name, $value, array $validValues, bool $throws = true)
    {
        if ($value === null) {
            return self::validateError(sprintf('Parameter %s must be passed', $name), $throws);
        }
        if (!\in_array($value, $validValues, false)) {
            return self::validateError(sprintf('Parameter %s is an invalid enum value', $name), $throws);
        }

        return $value;
    }

    /**
     * Throw a ValidatorException
     *
     * @param string $message
     * @param bool   $throws
     *
     * @return bool
     * @throws \Swoft\Exception\ValidatorException
     */
    private static function validateError(string $message, bool $throws): bool
    {
        if ($throws) {
            throw new ValidatorException($message);
        }

        return false;
    }
}
