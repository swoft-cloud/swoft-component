<?php declare(strict_types=1);


namespace Swoft\Validator;

use ReflectionException;
use function sprintf;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\BeanFactory;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Validator\Annotation\Mapping\IsArray;
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
use Swoft\Validator\Concern\ValidateItemTrait;
use Swoft\Validator\Contract\ValidatorInterface;
use Swoft\Validator\Exception\ValidatorException;

/**
 * Class Validator
 *
 * @since 2.0
 *
 * @Bean(name="validator")
 */
class Validator
{
    use ValidateItemTrait;

    /**
     * @param array  $data
     * @param string $className
     * @param string $method
     *
     * @return array
     * @throws ValidatorException
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function validate(array $data, string $className, string $method): array
    {
        $validates = ValidateRegister::getValidates($className, $method);
        if (empty($validates)) {
            return $data;
        }

        foreach ($validates as $validateName => $validate) {
            $validator = ValidatorRegister::getValidator($validateName);
            $type      = $validator['type'];
            $fields    = $validate['fields'] ?? [];
            $params    = $validate['params'] ?? [];

            // User validator
            if ($type == ValidatorRegister::TYPE_USER) {
                self::validateUserValidator($validateName, $data, $params);
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
    protected function validateDefaultValidator(array &$data, array $validator, array $fields): void
    {
        $properties = $validator['properties'] ?? [];
        foreach ($properties as $propName => $property) {
            if (!empty($fields) && !in_array($propName, $fields)) {
                continue;
            }

            /* @var IsString|IsInt|IsBool|IsFloat $type */
            $type        = $property['type']['annotation'] ?? null;
            $default     = $property['type']['default'] ?? null;
            $annotations = $property['annotations'] ?? [];
            if ($type === null) {
                continue;
            }

            $name     = $type->getName();
            $propName = (empty($name)) ? $propName : $name;

            $isDefault = self::validateDefaultItem($data, $propName, $type, $default);

            // Has set default value
            if ($isDefault) {
                continue;
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
     * @param mixed  $default
     *
     * @return bool
     * @throws ValidatorException
     */
    protected function validateDefaultItem(array &$data, string $propName, $item, $default = null): bool
    {
        $result    = false;
        $itemClass = get_class($item);
        switch ($itemClass) {
            case IsArray::class:
                $result = self::validateIsArray($data, $propName, $item, $default);
                break;
            case IsBool::class:
                $result = self::validateIsBool($data, $propName, $item, $default);
                break;
            case IsFloat::class:
                $result = self::validateIsFloat($data, $propName, $item, $default);
                break;
            case IsInt::class:
                $result = self::validateIsInt($data, $propName, $item, $default);
                break;
            case IsString::class:
                $result = self::validateIsString($data, $propName, $item, $default);
                break;
            case Email::class:
                self::validateEmail($data, $propName, $item);
                break;
            case Enum::class:
                self::validateEnum($data, $propName, $item);
                break;
            case Ip::class:
                self::validateIp($data, $propName, $item);
                break;
            case Length::class:
                self::validateLength($data, $propName, $item);
                break;
            case Max::class:
                self::validateMax($data, $propName, $item);
                break;
            case Min::class:
                self::validateMin($data, $propName, $item);
                break;
            case Mobile::class:
                self::validateMobile($data, $propName, $item);
                break;
            case NotEmpty::class:
                self::validateNotEmpty($data, $propName, $item);
                break;
            case Pattern::class:
                self::validatePattern($data, $propName, $item);
                break;
            case Range::class:
                self::validateRange($data, $propName, $item);
                break;
        }

        return $result;
    }

    /**
     * @param string $validateName
     * @param array  $data
     * @param array  $params
     *
     * @throws ValidatorException
     * @throws ReflectionException
     * @throws ContainerException
     */
    protected function validateUserValidator(string $validateName, array &$data, array $params): void
    {
        $validator = BeanFactory::getBean($validateName);
        if (!$validator instanceof ValidatorInterface) {
            throw new ValidatorException(
                sprintf('User validator(%s) must instance of ValidatorInterface', $validateName)
            );
        }

        $result = $validator->validate($data, $params);

        if ($result) {
            return;
        }

        throw new ValidatorException(
            sprintf('User validator(%s) must invalid!', $validateName)
        );
    }
}