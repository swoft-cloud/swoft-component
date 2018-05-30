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
     * @param string $name Parameter name
     * @param mixed $value Parameter value
     * @param int|null $min Parameter minimun value
     * @param int|null $max Parameter maximum value
     * @param bool $throws Determine if throw an ValidatorException when invalid
     * @param string $template
     *
     * @return mixed
     * @throws \Swoft\Exception\ValidatorException
     */
    public static function validateInteger(string $name, $value, $min = null, $max = null, bool $throws = true, string $template = '')
    {
        $params = [
            'name' => $name,
            'value' => $value,
            'min' => $min,
            'max' => $max,
        ];

        $template = self::getTemplate($template, $params);
        if ($value === null) {
            $template = empty($template) ? sprintf('Parameter %s must be passed', $name) : $template;

            return self::validateError($template, $throws);
        }

        if (!preg_match(self::$integerPattern, (string)$value)) {
            $template = empty($template) ? sprintf('Parameter %s is not integer type', $name) : $template;

            return self::validateError($template, $throws);
        }

        $value = (int)$value;
        if ($min !== null && $value < $min) {
            $template = empty($template) ? sprintf('Parameter %s is too small (minimum is %d)', $name, $min) : $template;

            return self::validateError($template, $throws);
        }

        if ($max !== null && $value > $max) {
            $template = empty($template) ? sprintf('Parameter %s is too big (maximum is %d)', $name, $max) : $template;

            return self::validateError($template, $throws);
        }

        return (int)$value;
    }

    /**
     * Validate number
     *
     * @param string $name Parameter name
     * @param mixed $value Parameter value
     * @param int|null $min Parameter minimun value
     * @param int|null $max Parameter maximum value
     * @param bool $throws Determine if throw an ValidatorException when invalid
     * @param string $template
     *
     * @return mixed
     * @throws \Swoft\Exception\ValidatorException
     */
    public static function validateNumber(string $name, $value, $min = null, $max = null, bool $throws = true, string $template)
    {
        $params = [
            'name' => $name,
            'value' => $value,
            'min' => $min,
            'max' => $max,
        ];

        $template = self::getTemplate($template, $params);
        if ($value === null) {
            $template = empty($template) ? sprintf('Parameter %s must be passed', $name) : $template;

            return self::validateError($template, $throws);
        }

        if (!preg_match(self::$numberPattern, (string)$value)) {
            $template = empty($template) ? sprintf('Parameter %s is not a number', $name) : $template;

            return self::validateError($template, $throws);
        }

        $value = (int)$value;
        if ($min !== null && $value < $min) {
            $template = empty($template) ? sprintf('Parameter %s is too small (minimum is %d)', $name, $min) : $template;

            return self::validateError($template, $throws);
        }

        if ($max !== null && $value > $max) {
            $template = empty($template) ? sprintf('Parameter %s is too big (maximum is %d)', $name, $max) : $template;

            return self::validateError($template, $throws);
        }

        return (int)$value;
    }

    /**
     * Validate float
     *
     * @param string $name Parameter name
     * @param mixed $value Parameter value
     * @param float|null $min Parameter minimun value
     * @param float|null $max Parameter maximum value
     * @param bool $throws Determine if throw an ValidatorException when invalid
     * @param string $template
     *
     * @throws ValidatorException
     * @return mixed
     */
    public static function validateFloat(
        string $name,
        $value,
        float $min = null,
        float $max = null,
        bool $throws = true,
        string $template
    )
    {
        $params = [
            'name' => $name,
            'value' => $value,
            'min' => $min,
            'max' => $max,
        ];

        $template = self::getTemplate($template, $params);
        if ($value === null) {
            $template = empty($template) ? sprintf('Parameter %s must be passed', $name) : $template;

            return self::validateError($template, $throws);
        }

        if (!preg_match(self::$floatPattern, (string)$value)) {
            $template = empty($template) ? sprintf('Parameter %s is not float type', $name) : $template;

            return self::validateError($template, $throws);
        }

        $value = (float)$value;
        if ($min !== null && $value < $min) {
            $template = empty($template) ? sprintf('Parameter %s is too small (minimum is %d)', $name, $min) : $template;

            return self::validateError($template, $throws);
        }

        if ($max !== null && $value > $max) {
            $template = empty($template) ? sprintf('Parameter %s is too big (maximum is %d)', $name, $max) : $template;

            return self::validateError($template, $throws);
        }

        return (float)$value;
    }

    /**
     * Validate string
     *
     * @param string $name Parameter name
     * @param mixed $value Parameter value
     * @param int|null $min Parameter length minimun value
     * @param int|null $max Parameter length maximum value
     * @param bool $throws Determine if throw an ValidatorException when invalid
     * @param string $template
     *
     * @return mixed
     * @throws \Swoft\Exception\ValidatorException
     */
    public static function validateString(string $name, $value, int $min = null, int $max = null, bool $throws = true, string $template)
    {
        $params = [
            'name' => $name,
            'value' => $value,
            'min' => $min,
            'max' => $max,
        ];

        $template = self::getTemplate($template, $params);
        if ($value === null) {
            $template = empty($template) ? sprintf('Parameter %s must be passed', $name) : $template;

            return self::validateError($template, $throws);
        }

        if (!\is_string($value)) {
            $template = empty($template) ? sprintf('Parameter %s is not string type', $name) : $template;

            return self::validateError($template, $throws);
        }
        $length = mb_strlen($value);
        if ($min !== null && $length < $min) {
            $template = empty($template) ? sprintf('Parameter %s length is too short (minimum is %d)', $name, $min) : $template;

            return self::validateError($template, $throws);
        }

        if ($max !== null && $length > $max) {
            $template = empty($template) ? sprintf('Parameter %s length is too long (maximum is %d)', $name, $max) : $template;

            return self::validateError($template, $throws);
        }

        return (string)$value;
    }

    /**
     * Validate enum
     *
     * @param string $name Parameter name
     * @param mixed $value Parameter value
     * @param array $validValues Enum values
     * @param bool $throws Determine if throw an ValidatorException when invalid
     * @param string $template
     *
     * @return mixed
     * @throws \Swoft\Exception\ValidatorException
     */
    public static function validateEnum(string $name, $value, array $validValues, bool $throws = true, string $template)
    {
        $params = [
            'name' => $name,
            'value' => JsonHelper::encode($value, JSON_UNESCAPED_UNICODE),
        ];

        $template = self::getTemplate($template, $params);
        if ($value === null) {
            $template = empty($template) ? sprintf('Parameter %s must be passed', $name) : $template;

            return self::validateError($template, $throws);
        }
        if (!\in_array($value, $validValues, false)) {
            $template = empty($template) ? sprintf('Parameter %s is an invalid enum value', $name) : $template;

            return self::validateError($template, $throws);
        }

        return $value;
    }

    /**
     * Validate email
     *
     * @param string $name Parameter name
     * @param mixed $value Parameter value
     * @param bool $throws Determine if throw an ValidatorException when invalid
     * @param string $template
     *
     * @return bool|string
     * @throws ValidatorException
     */
    public static function validateEmail(string $name, $value, bool $throws = true, string $template)
    {
        $params = [
            'name' => $name,
            'value' => $value,
        ];

        $template = self::getTemplate($template, $params);
        if ($value === null) {
            $template = empty($template) ? sprintf('Parameter %s must be passed', $name) : $template;

            return self::validateError($template, $throws);
        }

        if (!filter_var($value, \FILTER_VALIDATE_EMAIL)) {
            $template = empty($template) ? sprintf('Parameter %s is not email type', $name) : $template;

            return self::validateError($template, $throws);
        }

        return (string)$value;
    }

    /**
     * Validate ip
     *
     * @param string $name Parameter name
     * @param mixed $value Parameter value
     * @param bool $throws Determine if throw an ValidatorException when invalid
     * @param string $template
     *
     * @return bool|string
     * @throws ValidatorException
     */
    public static function validateIp(string $name, $value, bool $throws = true, string $template)
    {
        $params = [
            'name' => $name,
            'value' => $value,
        ];

        $template = self::getTemplate($template, $params);
        if ($value === null) {
            $template = empty($template) ? sprintf('Parameter %s must be passed', $name) : $template;

            return self::validateError($template, $throws);
        }

        if (!filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6)) {
            $template = empty($template) ? sprintf('Parameter %s is not ip type', $name) : $template;

            return self::validateError($template, $throws);
        }

        return (string)$value;
    }

    /**
     * Validate regex pattern
     *
     * @param string $name Parameter name
     * @param mixed $value Parameter value
     * @param string $pattern Regex pattern
     * @param bool $throws Determine if throw an ValidatorException when invalid
     * @param string $template
     *
     * @return bool|string
     * @throws ValidatorException
     */
    public static function validateRegex(string $name, $value, string $pattern, bool $throws = true, string $template)
    {
        $params = [
            'name' => $name,
            'value' => $value,
        ];

        $template = self::getTemplate($template, $params);
        if ($value === null) {
            $template = empty($template) ? sprintf('Parameter %s must be passed', $name) : $template;

            return self::validateError($template, $throws);
        }

        $matchPattern = preg_match($pattern, $value, $matches);

        if ($matchPattern === true) {
            $failed = ($matches[0] !== $value);
        } else {
            $failed = true;
        }

        if ($failed === true) {
            $template = empty($template) ? sprintf('Parameter %s is not rule', $name) : $template;

            return self::validateError($template, $throws);
        }

        return (string)$value;
    }

    /**
     * Validate date
     *
     * @param string $name Parameter name
     * @param mixed $value Parameter value
     * @param string $format Date format
     * @param bool $throws Determine if throw an ValidatorException when invalid
     * @param string $template
     *
     * @return bool|string
     * @throws ValidatorException
     */
    public static function validateDate(string $name, $value, string $format, bool $throws = true, string $template)
    {
        $params = [
            'name' => $name,
            'value' => $value,
        ];

        $template = self::getTemplate($template, $params);
        if ($value === null) {
            $template = empty($template) ? sprintf('Parameter %s must be passed', $name) : $template;

            return self::validateError($template, $throws);
        }

        $date = \DateTime::createFromFormat($format, $value);
        $errors = \DateTime::getLastErrors();
        unset($date);
        if ($errors["warning_count"] > 0 || $errors["error_count"] > 0) {
            $isDate = false;
        } else {
            $isDate = true;
        }

        if (!$isDate) {
            $template = empty($template) ? sprintf('Parameter %s is not date type', $name) : $template;

            return self::validateError($template, $throws);
        }

        return (string)$value;
    }

    /**
     * Validate credit card
     * @param string $name Parameter name
     * @param mixed $value Parameter value
     * @param bool $throws Determine if throw an ValidatorException when invalid
     * @param string $template
     *
     * @return bool|string
     * @throws ValidatorException
     */
    public static function validateCreditCard(string $name, $value, bool $throws = true, string $template)
    {
        $params = [
            'name' => $name,
            'value' => $value,
        ];

        $template = self::getTemplate($template, $params);
        if ($value === null) {
            $template = empty($template) ? sprintf('Parameter %s must be passed', $name) : $template;

            return self::validateError($template, $throws);
        }

        //validator credit card start
        $digits = (array)str_split($value);
        $hash = '';
        foreach (array_reverse($digits) as $position => $digit) {
            $hash .= ($position % 2 ? $digit * 2 : $digit);
        }
        $result = array_sum(str_split($hash));

        $isCreditCard = (bool)($result % 10 == 0);
        //validator credit card end

        if (!$isCreditCard) {
            $template = empty($template) ? sprintf('Parameter %s is not credit card type', $name) : $template;

            return self::validateError($template, $throws);
        }

        return (string)$value;
    }

    /**
     * Validate url
     * @param string $name Parameter name
     * @param mixed $value Parameter value
     * @param bool $throws Determine if throw an ValidatorException when invalid
     * @param string $template
     * @return bool|string
     * @throws ValidatorException
     */
    public static function validateUrl(string $name, $value, bool $throws = true, string $template)
    {
        $params = [
            'name' => $name,
            'value' => $value,
        ];

        $template = self::getTemplate($template, $params);
        if ($value === null) {
            $template = empty($template) ? sprintf('Parameter %s must be passed', $name) : $template;

            return self::validateError($template, $throws);
        }

        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            $template = empty($template) ? sprintf('Parameter %s is not credit card type', $name) : $template;

            return self::validateError($template, $throws);
        }

        return (string)$value;
    }

    /**
     * Validate alphanumeric
     *
     * @param string $name Parameter name
     * @param mixed $value Parameter value
     * @param int|null $min Parameter length minimun value
     * @param int|null $max Parameter length maximum value
     * @param bool $throws Determine if throw an ValidatorException when invalid
     * @param string $template
     * @return bool|string
     * @throws ValidatorException
     */
    public static function validateAlphanumeric(string $name, $value, int $min = null, int $max = null, bool $throws = true, string $template)
    {
        $params = [
            'name' => $name,
            'value' => $value,
            'min' => $min,
            'max' => $max,
        ];

        $template = self::getTemplate($template, $params);
        if ($value === null) {
            $template = empty($template) ? sprintf('Parameter %s must be passed', $name) : $template;

            return self::validateError($template, $throws);
        }

        if (!ctype_alnum($value)) {
            $template = empty($template) ? sprintf('Parameter %s is not alphanumeric type', $name) : $template;

            return self::validateError($template, $throws);
        }

        $length = mb_strlen($value);
        if ($min !== null && $length < $min) {
            $template = empty($template) ? sprintf('Parameter %s length is too short (minimum is %d)', $name, $min) : $template;

            return self::validateError($template, $throws);
        }

        if ($max !== null && $length > $max) {
            $template = empty($template) ? sprintf('Parameter %s length is too long (maximum is %d)', $name, $max) : $template;

            return self::validateError($template, $throws);
        }

        return (string)$value;
    }

    /**
     * Validate alphabetic
     *
     * @param string $name Parameter name
     * @param mixed $value Parameter value
     * @param int|null $min Parameter length minimun value
     * @param int|null $max Parameter length maximum value
     * @param bool $throws Determine if throw an ValidatorException when invalid
     * @param string $template
     *
     * @return bool|string
     * @throws ValidatorException
     */
    public static function validateAlphabetic(string $name, $value, int $min = null, int $max = null, bool $throws = true, string $template)
    {
        $params = [
            'name' => $name,
            'value' => $value,
            'min' => $min,
            'max' => $max,
        ];

        $template = self::getTemplate($template, $params);
        if ($value === null) {
            $template = empty($template) ? sprintf('Parameter %s must be passed', $name) : $template;

            return self::validateError($template, $throws);
        }

        if (preg_match("/[^[:alpha:]]/imu", $value)) {
            $template = empty($template) ? sprintf('Parameter %s is not alphabetic type', $name) : $template;

            return self::validateError($template, $throws);
        }

        $length = mb_strlen($value);
        if ($min !== null && $length < $min) {
            $template = empty($template) ? sprintf('Parameter %s length is too short (minimum is %d)', $name, $min) : $template;

            return self::validateError($template, $throws);
        }

        if ($max !== null && $length > $max) {
            $template = empty($template) ? sprintf('Parameter %s length is too long (maximum is %d)', $name, $max) : $template;

            return self::validateError($template, $throws);
        }

        return (string)$value;
    }

    /**
     * Validate value use callback
     *
     * @param string $name Parameter name
     * @param mixed $value Parameter value
     * @param string $callback is can callback function
     * @param bool $throws Determine if throw an ValidatorException when invalid
     * @param string $template
     * @return bool|string
     * @throws ValidatorException
     */
    public static function validateCallback(string $name, $value, string $callback, bool $throws = true, string $template)
    {
        $params = [
            'name' => $name,
            'value' => $value,
        ];

        $template = self::getTemplate($template, $params);
        if ($value === null) {
            $template = empty($template) ? sprintf('Parameter %s must be passed', $name) : $template;

            return self::validateError($template, $throws);
        }
        $validateValue = call_user_func($callback, $value);
        if (!$validateValue) {
            $template = empty($template) ? sprintf('Parameter %s is can not validate value', $name) : $template;

            return self::validateError($template, $throws);
        }

        return (string)$value;
    }

    /**
     * @param string $template
     * @param array $params
     *
     * @return string
     */
    private static function getTemplate(string $template, array $params): string
    {
        if (empty($template)) {
            return '';
        }
        $names = array_keys($params);
        $replace = array_values($params);
        $search = array_map(function ($v) {
            return sprintf('{%s}', $v);
        }, $names);

        return str_replace($search, $replace, $template);
    }

    /**
     * Throw a ValidatorException
     *
     * @param string $message
     * @param bool $throws
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
