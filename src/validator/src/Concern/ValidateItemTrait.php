<?php declare(strict_types=1);


namespace Swoft\Validator\Concern;

use Swoft\Validator\Annotation\Mapping\BoolType;
use Swoft\Validator\Annotation\Mapping\Email;
use Swoft\Validator\Annotation\Mapping\Enum;
use Swoft\Validator\Annotation\Mapping\FloatType;
use Swoft\Validator\Annotation\Mapping\IntType;
use Swoft\Validator\Annotation\Mapping\Ip;
use Swoft\Validator\Annotation\Mapping\Length;
use Swoft\Validator\Annotation\Mapping\StringType;
use Swoft\Validator\Exception\ValidatorException;
use Swoft\Validator\Helper\ValidatorHelper;

/**
 * Class ValidateItemTrait
 *
 * @since 2.0
 */
trait ValidateItemTrait
{
    /**
     * @param array  $data
     * @param string $propertyName
     * @param object $item
     *
     * @return bool
     *
     * @throws ValidatorException
     */
    protected static function validateBoolType(array &$data, string $propertyName, $item): bool
    {
        /* @var BoolType $item */
        $message = $item->getMessage();
        $default = $item->getDefault();

        if (!isset($data[$propertyName]) && $default !== null) {
            if ($default == 'true') {
                $data[$propertyName] = true;
            } elseif ($default == 'false') {
                $data[$propertyName] = false;
            } else {
                $data[$propertyName] = false;
            }

            return true;
        }

        if (!isset($data[$propertyName]) && $default === null) {
            $message = (empty($message)) ? \sprintf('Param(%s) must exist!', $propertyName) : $message;
            throw new ValidatorException($message);
        }

        $value = $data[$propertyName];
        if (is_bool($value)) {
            return false;
        }

        $message = (empty($message)) ? \sprintf('Param(%s) must bool!', $propertyName) : $message;
        throw new ValidatorException($message);
    }

    /**
     * @param array  $data
     * @param string $propertyName
     * @param object $item
     *
     * @return bool
     * @throws ValidatorException
     */
    protected static function validateFloatType(array &$data, string $propertyName, $item): bool
    {
        /* @var FloatType $item */
        $message = $item->getMessage();
        $default = $item->getDefault();

        if (!isset($data[$propertyName]) && $default !== null) {
            $data[$propertyName] = (float)$default;
            return true;
        }

        if (!isset($data[$propertyName]) && $default === null) {
            $message = (empty($message)) ? \sprintf('Param(%s) must exist!', $propertyName) : $message;
            throw new ValidatorException($message);
        }

        $value = $data[$propertyName];
        if (is_float($value)) {
            return false;
        }

        $message = (empty($message)) ? \sprintf('Param(%s) must float!', $propertyName) : $message;
        throw new ValidatorException($message);
    }

    /**
     * @param array  $data
     * @param string $propertyName
     * @param object $item
     *
     * @return bool
     * @throws ValidatorException
     */
    protected static function validateIntType(array &$data, string $propertyName, $item): bool
    {
        /* @var IntType $item */
        $message = $item->getMessage();
        $default = $item->getDefault();

        if (!isset($data[$propertyName]) && $default !== null) {
            $data[$propertyName] = (int)$default;
            return true;
        }

        if (!isset($data[$propertyName]) && $default === null) {
            $message = (empty($message)) ? \sprintf('Param(%s) must exist!', $propertyName) : $message;
            throw new ValidatorException($message);
        }

        $value = $data[$propertyName];
        if (is_int($value)) {
            return false;
        }

        $message = (empty($message)) ? \sprintf('Param(%s) must int!', $propertyName) : $message;
        throw new ValidatorException($message);
    }

    /**
     * @param array  $data
     * @param string $propertyName
     * @param object $item
     *
     * @return bool
     * @throws ValidatorException
     */
    protected static function validateStringType(array &$data, string $propertyName, $item): bool
    {
        /* @var StringType $item */
        $message = $item->getMessage();
        $default = $item->getDefault();

        if (!isset($data[$propertyName]) && $default !== null) {
            $data[$propertyName] = (string)$default;
            return true;
        }

        if (!isset($data[$propertyName]) && $default === null) {
            $message = (empty($message)) ? \sprintf('Param(%s) must exist!', $propertyName) : $message;
            throw new ValidatorException($message);
        }

        $value = $data[$propertyName];
        if (is_string($value)) {
            return false;
        }

        $message = (empty($message)) ? \sprintf('Param(%s) must string!', $propertyName) : $message;
        throw new ValidatorException($message);
    }

    /**
     * @param array  $data
     * @param string $propertyName
     * @param object $item
     *
     * @throws ValidatorException
     */
    protected static function validateEmail(array &$data, string $propertyName, $item): void
    {
        $value = $data[$propertyName];
        if (ValidatorHelper::validateEmail($value)) {
            return;
        }

        /* @var Email $item */
        $message = $item->getMessage();
        $message = (empty($message)) ? \sprintf('%s must be a email', $propertyName) : $message;

        throw new ValidatorException($message);
    }

    /**
     * @param array  $data
     * @param string $propertyName
     * @param object $item
     *
     * @throws ValidatorException
     */
    protected static function validateEnum(array &$data, string $propertyName, $item): void
    {
        /* @var Enum $item */
        $values = $item->getValues();
        $value  = $data[$propertyName];
        if (ValidatorHelper::validateEnum($value, $values)) {
            return;
        }

        $message = $item->getMessage();
        $message = (empty($message)) ? \sprintf('%s is invalid enum', $propertyName) : $message;

        throw new ValidatorException($message);
    }

    /**
     * @param array  $data
     * @param string $propertyName
     * @param object $item
     *
     * @throws ValidatorException
     */
    protected static function validateIp(array &$data, string $propertyName, $item): void
    {
        $value = $data[$propertyName];
        if (ValidatorHelper::validateIp($value)) {
            return;
        }

        /* @var Ip $item */
        $message = $item->getMessage();
        $message = (empty($message)) ? \sprintf('%s is invalid ip', $propertyName) : $message;

        throw new ValidatorException($message);
    }

    /**
     * @param array  $data
     * @param string $propertyName
     * @param object $item
     *
     * @throws ValidatorException
     */
    protected static function validateLength(array &$data, string $propertyName, $item): void
    {
        /* @var Length $item */
        $min = $item->getMin();
        $max = $item->getMax();

        $value = $data[$propertyName];
        if (ValidatorHelper::validatelength($value, $min, $max)) {
            return;
        }

        /* @var Ip $item */
        $message = $item->getMessage();
        $message = (empty($message)) ? \sprintf('%s is invalid length(min=%d, max=%d)', $propertyName, $min,
            $max) : $message;

        throw new ValidatorException($message);
    }

    protected static function validateMax(array &$data, string $propertyName, $item): void
    {
        /* @var StringType $item */

    }

    protected static function validateMin(array &$data, string $propertyName, $item): void
    {
        /* @var StringType $item */

    }

    protected static function validateMobile(array &$data, string $propertyName, $item): void
    {
        /* @var StringType $item */

    }

    protected static function validateNotEmpty(array &$data, string $propertyName, $item): void
    {
        /* @var StringType $item */

    }

    protected static function validatePattern(array &$data, string $propertyName, $item): void
    {
        /* @var StringType $item */

    }

    protected static function validateRange(array &$data, string $propertyName, $item): void
    {
        /* @var StringType $item */

    }
}