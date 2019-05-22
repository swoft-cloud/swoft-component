<?php declare(strict_types=1);


namespace Swoft\Validator\Concern;

use function is_array;
use function sprintf;
use Swoft\Validator\Annotation\Mapping\IsBool;
use Swoft\Validator\Annotation\Mapping\Email;
use Swoft\Validator\Annotation\Mapping\Enum;
use Swoft\Validator\Annotation\Mapping\IsFloat;
use Swoft\Validator\Annotation\Mapping\IsInt;
use Swoft\Validator\Annotation\Mapping\Ip;
use Swoft\Validator\Annotation\Mapping\Length;
use Swoft\Validator\Annotation\Mapping\Max;
use Swoft\Validator\Annotation\Mapping\Min;
use Swoft\Validator\Annotation\Mapping\Mobile;
use Swoft\Validator\Annotation\Mapping\NotEmpty;
use Swoft\Validator\Annotation\Mapping\Pattern;
use Swoft\Validator\Annotation\Mapping\Range;
use Swoft\Validator\Annotation\Mapping\IsString;
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
     * @param mixed  $default
     *
     * @return bool
     *
     * @throws ValidatorException
     */
    protected function validateIsArray(array &$data, string $propertyName, $item, $default): bool
    {
        /* @var IsBool $item */
        $message = $item->getMessage();

        if (!isset($data[$propertyName]) && $default !== null) {
            $data[$propertyName] = (array)$default;

            return true;
        }

        if (!isset($data[$propertyName]) && $default === null) {
            $message = (empty($message)) ? sprintf('%s must exist!', $propertyName) : $message;
            throw new ValidatorException($message);
        }

        $value = $data[$propertyName];
        if (is_array($value)) {
            return false;
        }

        $message = (empty($message)) ? sprintf('%s must bool!', $propertyName) : $message;
        throw new ValidatorException($message);
    }

    /**
     * @param array  $data
     * @param string $propertyName
     * @param object $item
     * @param mixed  $default
     *
     * @return bool
     *
     * @throws ValidatorException
     */
    protected function validateIsBool(array &$data, string $propertyName, $item, $default): bool
    {
        /* @var IsBool $item */
        $message = $item->getMessage();
        if (!isset($data[$propertyName]) && $default !== null) {
            $data[$propertyName] = (bool)$default;

            return true;
        }

        if (!isset($data[$propertyName]) && $default === null) {
            $message = (empty($message)) ? sprintf('%s must exist!', $propertyName) : $message;
            throw new ValidatorException($message);
        }

        $value = $data[$propertyName];
        if ($value == 'true' || $value == 'false' || is_bool($value)) {
            $data[$propertyName] = (bool)$value;
            return false;
        }

        $message = (empty($message)) ? sprintf('%s must bool!', $propertyName) : $message;
        throw new ValidatorException($message);
    }

    /**
     * @param array  $data
     * @param string $propertyName
     * @param object $item
     * @param mixed  $default
     *
     * @return bool
     * @throws ValidatorException
     */
    protected function validateIsFloat(array &$data, string $propertyName, $item, $default): bool
    {
        /* @var IsFloat $item */
        $message = $item->getMessage();

        if (!isset($data[$propertyName]) && $default !== null) {
            $data[$propertyName] = (float)$default;
            return true;
        }

        if (!isset($data[$propertyName]) && $default === null) {
            $message = (empty($message)) ? sprintf('%s must exist!', $propertyName) : $message;
            throw new ValidatorException($message);
        }

        $value = $data[$propertyName];
        $value = filter_var($value, FILTER_VALIDATE_FLOAT);
        if ($value !== false) {
            $data[$propertyName] = $value;
            return false;
        }

        $message = (empty($message)) ? sprintf('%s must float!', $propertyName) : $message;
        throw new ValidatorException($message);
    }

    /**
     * @param array  $data
     * @param string $propertyName
     * @param object $item
     * @param mixed  $default
     *
     * @return bool
     * @throws ValidatorException
     */
    protected function validateIsInt(array &$data, string $propertyName, $item, $default): bool
    {
        /* @var IsInt $item */
        $message = $item->getMessage();

        if (!isset($data[$propertyName]) && $default !== null) {
            $data[$propertyName] = (int)$default;
            return true;
        }

        if (!isset($data[$propertyName]) && $default === null) {
            $message = (empty($message)) ? sprintf('%s must exist!', $propertyName) : $message;
            throw new ValidatorException($message);
        }

        $value = $data[$propertyName];
        $value = filter_var($value, FILTER_VALIDATE_INT);
        if ($value !== false) {
            $data[$propertyName] = $value;
            return false;
        }

        $message = (empty($message)) ? sprintf('%s must int!', $propertyName) : $message;
        throw new ValidatorException($message);
    }

    /**
     * @param array  $data
     * @param string $propertyName
     * @param object $item
     * @param mixed  $default
     *
     * @return bool
     * @throws ValidatorException
     */
    protected function validateIsString(array &$data, string $propertyName, $item, $default): bool
    {
        /* @var IsString $item */
        $message = $item->getMessage();
        if (!isset($data[$propertyName]) && $default !== null) {
            $data[$propertyName] = (string)$default;
            return true;
        }

        if (!isset($data[$propertyName]) && $default === null) {
            $message = (empty($message)) ? sprintf('%s must exist!', $propertyName) : $message;
            throw new ValidatorException($message);
        }

        $value = $data[$propertyName];
        if (is_string($value)) {
            return false;
        }

        $message = (empty($message)) ? sprintf('%s must string!', $propertyName) : $message;
        throw new ValidatorException($message);
    }

    /**
     * @param array  $data
     * @param string $propertyName
     * @param object $item
     *
     * @throws ValidatorException
     */
    protected function validateEmail(array &$data, string $propertyName, $item): void
    {
        $value = $data[$propertyName];
        if (ValidatorHelper::validateEmail($value)) {
            return;
        }

        /* @var Email $item */
        $message = $item->getMessage();
        $message = (empty($message)) ? sprintf('%s must be a email', $propertyName) : $message;

        throw new ValidatorException($message);
    }

    /**
     * @param array  $data
     * @param string $propertyName
     * @param object $item
     *
     * @throws ValidatorException
     */
    protected function validateEnum(array &$data, string $propertyName, $item): void
    {
        /* @var Enum $item */
        $values = $item->getValues();
        $value  = $data[$propertyName];
        if (ValidatorHelper::validateEnum($value, $values)) {
            return;
        }

        $message = $item->getMessage();
        $message = (empty($message)) ? sprintf('%s is invalid enum', $propertyName) : $message;

        throw new ValidatorException($message);
    }

    /**
     * @param array  $data
     * @param string $propertyName
     * @param object $item
     *
     * @throws ValidatorException
     */
    protected function validateIp(array &$data, string $propertyName, $item): void
    {
        $value = $data[$propertyName];
        if (ValidatorHelper::validateIp($value)) {
            return;
        }

        /* @var Ip $item */
        $message = $item->getMessage();
        $message = (empty($message)) ? sprintf('%s is invalid ip', $propertyName) : $message;

        throw new ValidatorException($message);
    }

    /**
     * @param array  $data
     * @param string $propertyName
     * @param object $item
     *
     * @throws ValidatorException
     */
    protected function validateLength(array &$data, string $propertyName, $item): void
    {
        /* @var Length $item */
        $min = $item->getMin();
        $max = $item->getMax();

        $value = $data[$propertyName];
        if (ValidatorHelper::validatelength($value, $min, $max)) {
            return;
        }

        $message = $item->getMessage();
        $message = (empty($message)) ? sprintf('%s is invalid length(min=%d, max=%d)', $propertyName, $min,
            $max) : $message;

        throw new ValidatorException($message);
    }

    /**
     * @param array  $data
     * @param string $propertyName
     * @param object $item
     *
     * @throws ValidatorException
     */
    protected function validateMax(array &$data, string $propertyName, $item): void
    {
        /* @var Max $item */
        $max   = $item->getValue();
        $value = $data[$propertyName];
        if ($value <= $max) {
            return;
        }

        $message = $item->getMessage();
        $message = (empty($message)) ? sprintf('%s is too big(max=%d)', $propertyName, $max) : $message;

        throw new ValidatorException($message);
    }

    /**
     * @param array  $data
     * @param string $propertyName
     * @param object $item
     *
     * @throws ValidatorException
     */
    protected function validateMin(array &$data, string $propertyName, $item): void
    {
        /* @var Min $item */
        $min   = $item->getValue();
        $value = $data[$propertyName];
        if ($value >= $min) {
            return;
        }

        $message = $item->getMessage();
        $message = (empty($message)) ? sprintf('%s is too small(min=%d)', $propertyName, $min) : $message;

        throw new ValidatorException($message);

    }

    /**
     * @param array  $data
     * @param string $propertyName
     * @param object $item
     *
     * @throws ValidatorException
     */
    protected function validateMobile(array &$data, string $propertyName, $item): void
    {
        /* @var Mobile $item */
        $value = $data[$propertyName];
        if (ValidatorHelper::validateMobile($value)) {
            return;
        }

        $message = $item->getMessage();
        $message = (empty($message)) ? sprintf('%s is invalid mobile!', $propertyName) : $message;
        throw new ValidatorException($message);
    }

    /**
     * @param array  $data
     * @param string $propertyName
     * @param object $item
     *
     * @throws ValidatorException
     */
    protected function validateNotEmpty(array &$data, string $propertyName, $item): void
    {
        /* @var NotEmpty $item */
        $value = $data[$propertyName];
        if (!empty($value)) {
            return;
        }

        $message = $item->getMessage();
        $message = (empty($message)) ? sprintf('%s can not be empty!', $propertyName) : $message;
        throw new ValidatorException($message);
    }

    /**
     * @param array  $data
     * @param string $propertyName
     * @param object $item
     *
     * @throws ValidatorException
     */
    protected function validatePattern(array &$data, string $propertyName, $item): void
    {
        /* @var Pattern $item */
        $regex = $item->getRegex();
        $value = $data[$propertyName];
        if (ValidatorHelper::validatePattern($value, $regex)) {
            return;
        }

        $message = $item->getMessage();
        $message = (empty($message)) ? sprintf('%s is invalid pattern!', $propertyName) : $message;
        throw new ValidatorException($message);
    }

    /**
     * @param array  $data
     * @param string $propertyName
     * @param object $item
     *
     * @throws ValidatorException
     */
    protected function validateRange(array &$data, string $propertyName, $item): void
    {
        /* @var Range $item */
        $min = $item->getMin();
        $max = $item->getMax();

        $value = $data[$propertyName];
        if (ValidatorHelper::validateRange($value, $min, $max)) {
            return;
        }

        $message = $item->getMessage();
        $message = (empty($message)) ? sprintf('%s is invalid range(min=%d, max=%d)', $propertyName, $min,
            $max) : $message;

        throw new ValidatorException($message);
    }
}