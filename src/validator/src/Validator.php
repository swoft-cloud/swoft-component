<?php declare(strict_types=1);


namespace Swoft\Validator;

use Swoft\Validator\Annotation\Mapping\ArrayType;
use Swoft\Validator\Annotation\Mapping\BoolType;
use Swoft\Validator\Annotation\Mapping\FloatType;
use Swoft\Validator\Annotation\Mapping\IntType;
use Swoft\Validator\Annotation\Mapping\StringType;
use Swoft\Validator\Exception\ValidatorException;

/**
 * Class Validator
 *
 * @since 2.0
 */
class Validator
{
    /**
     * @param array  $data
     * @param string $className
     * @param string $method
     *
     * @return array
     * @throws ValidatorException
     */
    public static function validate(array $data, string $className, string $method): array
    {
        $validates = ValidateRegister::getValidates($className, $method);
        if (empty($validates)) {
            return $data;
        }

        $fields = $validates['fields'] ?? [];
        foreach ($validates as $validateName) {
            $validator = ValidatorRegister::getValidator($validateName);
            $type      = $validator['type'];

            // User validator
            if ($type == ValidatorRegister::TYPE_USER) {

                continue;
            }

            self::validateDefaultValidator($data, $validator, $fields);
        }

        return $data;
    }

    /**
     * @param array $data
     * @param array $validator
     * @param array $fields
     *
     * @throws ValidatorException
     */
    private static function validateDefaultValidator(array &$data, array $validator, array $fields): void
    {
        $properties = $validator['properties'] ?? [];

        foreach ($properties as $propName => $property) {
            if (!empty($fields) && !in_array($propName, $fields)) {
                continue;
            }

            $type        = $property['type'] ?? null;
            $annotations = $properties['annotations'] ?? [];
            if ($type !== null) {
                self::validateDefaultItem($data, $propName, $type);
            }

            foreach ($annotations as $annotation) {
                self::validateDefaultItem($data, $propName, $annotation);
            }
        }
    }

    /**
     * @param array  $data
     * @param string $propName
     * @param object $item
     *
     * @throws ValidatorException
     */
    private static function validateDefaultItem(array &$data, string $propName, $item)
    {
        $itemClass = get_class($item);
        switch ($itemClass) {
            case ArrayType::class:

                break;
            case BoolType::class:
                self::validateBoolType($data, $propName, $item);
                break;
            case FloatType::class:
                self::validateFloatType($data, $propName, $item);
                break;
            case IntType::class:
                self::validateIntType($data, $propName, $item);

                break;
            case StringType::class:
                self::validateStringType($data, $propName, $item);
                break;
        }
    }

    /**
     * @param array  $data
     * @param string $propertyName
     * @param object $item
     *
     * @throws ValidatorException
     */
    private static function validateBoolType(array &$data, string $propertyName, $item): void
    {
        if (!$item instanceof BoolType) {
            return;
        }

        $name    = $item->getName();
        $message = $item->getMessage();
        $default = $item->getDefault();

        $checkName = (empty($name)) ? $propertyName : $name;

        if (!isset($data[$checkName]) && $default !== null) {
            if ($default == 'true') {
                $data[$checkName] = true;
            } elseif ($default == 'false') {
                $data[$checkName] = false;
            } else {
                $data[$checkName] = false;
            }

            return;
        }

        if (!isset($data[$checkName]) && $default === null) {
            $message = (empty($message)) ? \sprintf('Param(%s) must exist!', $checkName) : $message;
            throw new ValidatorException($message);
        }

        $value = $data[$checkName];
        if (is_bool($value)) {
            return;
        }

        $message = (empty($message)) ? \sprintf('Param(%s) must bool!', $checkName) : $message;
        throw new ValidatorException($message);
    }

    /**
     * @param array  $data
     * @param string $propertyName
     * @param object $item
     *
     * @throws ValidatorException
     */
    private static function validateFloatType(array &$data, string $propertyName, $item): void
    {
        if (!$item instanceof FloatType) {
            return;
        }

        $name    = $item->getName();
        $message = $item->getMessage();
        $default = $item->getDefault();

        $checkName = (empty($name)) ? $propertyName : $name;

        if (!isset($data[$checkName]) && $default !== null) {
            $data[$checkName] = (float)$default;
            return;
        }

        if (!isset($data[$checkName]) && $default === null) {
            $message = (empty($message)) ? \sprintf('Param(%s) must exist!', $checkName) : $message;
            throw new ValidatorException($message);
        }

        $value = $data[$checkName];
        if (is_float($value)) {
            return;
        }

        $message = (empty($message)) ? \sprintf('Param(%s) must float!', $checkName) : $message;
        throw new ValidatorException($message);
    }

    /**
     * @param array  $data
     * @param string $propertyName
     * @param object $item
     *
     * @throws ValidatorException
     */
    private static function validateIntType(array &$data, string $propertyName, $item): void
    {
        if (!$item instanceof IntType) {
            return;
        }

        $name    = $item->getName();
        $message = $item->getMessage();
        $default = $item->getDefault();

        $checkName = (empty($name)) ? $propertyName : $name;

        if (!isset($data[$checkName]) && $default !== null) {
            $data[$checkName] = (int)$default;
            return;
        }

        if (!isset($data[$checkName]) && $default === null) {
            $message = (empty($message)) ? \sprintf('Param(%s) must exist!', $checkName) : $message;
            throw new ValidatorException($message);
        }

        $value = $data[$checkName];
        if (is_int($value)) {
            return;
        }

        $message = (empty($message)) ? \sprintf('Param(%s) must int!', $checkName) : $message;
        throw new ValidatorException($message);
    }

    /**
     * @param array  $data
     * @param string $propertyName
     * @param object $item
     *
     * @throws ValidatorException
     */
    private static function validateStringType(array &$data, string $propertyName, $item): void
    {
        if (!$item instanceof StringType) {
            return;
        }

        $name    = $item->getName();
        $message = $item->getMessage();
        $default = $item->getDefault();

        $checkName = (empty($name)) ? $propertyName : $name;

        if (!isset($data[$checkName]) && $default !== null) {
            $data[$checkName] = (string)$default;
            return;
        }

        if (!isset($data[$checkName]) && $default === null) {
            $message = (empty($message)) ? \sprintf('Param(%s) must exist!', $checkName) : $message;
            throw new ValidatorException($message);
        }

        $value = $data[$checkName];
        if (is_string($value)) {
            return;
        }

        $message = (empty($message)) ? \sprintf('Param(%s) must string!', $checkName) : $message;
        throw new ValidatorException($message);
    }
}