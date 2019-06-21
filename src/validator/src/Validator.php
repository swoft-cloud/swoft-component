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
use Swoft\Validator\Annotation\Mapping\ValidateType;
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

    /***
     * @param array  $data
     * @param string $validatorName
     * @param array  $fields
     * @param array  $userValidators
     *
     * @return array
     * @throws ContainerException
     * @throws ReflectionException
     * @throws ValidatorException
     */
    public function validate(array $data, string $validatorName, array $fields = [], array $userValidators = []): array
    {
        if (empty($data)) {
            throw new ValidatorException('Validator data is empty!');
        }

        $type      = ValidatorRegister::TYPE_DEFAULT;
        $validator = ValidatorRegister::getValidator($validatorName);

        if (empty($validator)) {
            throw new ValidatorException(
                sprintf('Validator(%s) is not exist!', $validatorName)
            );
        }

        $data = $this->validateValidator($data, $type, $validatorName, [], $validator, $fields);
        if (empty($userValidators)) {
            return $data;
        }

        foreach ($userValidators as $userValidator => $params) {
            if (is_int($userValidator)) {
                $userValidator = $params;
                $params        = [];
            }

            $validator = ValidatorRegister::getValidator($userValidator);

            // Check type
            $type = $validator['type'];
            if ($type != ValidatorRegister::TYPE_USER) {
                throw new ValidatorException(
                    sprintf('Validator(%s) is user validator!', $userValidator)
                );
            }

            $data = $this->validateValidator($data, $type, $userValidator, $params, $validator, $fields);
        }

        return $data;
    }

    /**
     * @param array $body
     * @param array $validates
     * @param array $query
     *
     * @return array
     * @throws ContainerException
     * @throws ReflectionException
     * @throws ValidatorException
     */
    public function validateRequest(array $body, array $validates, array $query = []): array
    {
        foreach ($validates as $validateName => $validate) {
            $validator = ValidatorRegister::getValidator($validateName);

            if (empty($validator)) {
                throw new ValidatorException(
                    sprintf('Validator(%s) is not exist!', $validateName)
                );
            }

            $type   = $validator['type'];
            $fields = $validate['fields'] ?? [];
            $params = $validate['params'] ?? [];

            $validateType = $validate['type'];

            // Get query params
            if ($validateType == ValidateType::GET) {
                $query = $this->validateValidator($query, $type, $validateName, $params, $validator, $fields);
                continue;
            }

            $body = $this->validateValidator($body, $type, $validateName, $params, $validator, $fields);
        }
        return [$body, $query];
    }

    /**
     * @param array  $data
     * @param int    $type
     * @param string $validateName
     * @param array  $params
     * @param array  $validator
     * @param array  $fields
     *
     * @return array
     * @throws ContainerException
     * @throws ReflectionException
     * @throws ValidatorException
     */
    protected function validateValidator(
        array $data,
        int $type,
        string $validateName,
        array $params,
        array $validator,
        array $fields
    ): array {
        // User validator
        if ($type == ValidatorRegister::TYPE_USER) {
            return $this->validateUserValidator($validateName, $data, $params);
        }

        return $this->validateDefaultValidator($data, $validator, $fields);
    }

    /**
     * @param array $data
     * @param array $validator
     * @param array $fields
     *
     * @return array
     * @throws ValidatorException
     */
    protected function validateDefaultValidator(array $data, array $validator, array $fields): array
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

            // Default validate item(Type) and other item
            $data = $this->validateDefaultItem($data, $propName, $type, $default);
            foreach ($annotations as $annotation) {
                $data = $this->validateDefaultItem($data, $propName, $annotation);
            }
        }

        return $data;
    }

    /**
     * @param array  $data
     * @param string $propName
     * @param object $item
     * @param mixed  $default
     *
     * @return array
     * @throws ValidatorException
     */
    protected function validateDefaultItem(array $data, string $propName, $item, $default = null): array
    {
        $itemClass = get_class($item);
        switch ($itemClass) {
            case IsArray::class:
                $data = $this->validateIsArray($data, $propName, $item, $default);
                break;
            case IsBool::class:
                $data = $this->validateIsBool($data, $propName, $item, $default);
                break;
            case IsFloat::class:
                $data = $this->validateIsFloat($data, $propName, $item, $default);
                break;
            case IsInt::class:
                $data = $this->validateIsInt($data, $propName, $item, $default);
                break;
            case IsString::class:
                $data = $this->validateIsString($data, $propName, $item, $default);
                break;
            case Email::class:
                $data = $this->validateEmail($data, $propName, $item);
                break;
            case Enum::class:
                $data = $this->validateEnum($data, $propName, $item);
                break;
            case Ip::class:
                $data = $this->validateIp($data, $propName, $item);
                break;
            case Length::class:
                $data = $this->validateLength($data, $propName, $item);
                break;
            case Max::class:
                $data = $this->validateMax($data, $propName, $item);
                break;
            case Min::class:
                $data = $this->validateMin($data, $propName, $item);
                break;
            case Mobile::class:
                $data = $this->validateMobile($data, $propName, $item);
                break;
            case NotEmpty::class:
                $data = $this->validateNotEmpty($data, $propName, $item);
                break;
            case Pattern::class:
                $data = $this->validatePattern($data, $propName, $item);
                break;
            case Range::class:
                $data = $this->validateRange($data, $propName, $item);
                break;
        }

        return $data;
    }

    /**
     * @param string $validateName
     * @param array  $data
     * @param array  $params
     *
     * @return array
     * @throws ValidatorException
     * @throws ReflectionException
     * @throws ContainerException
     */
    protected function validateUserValidator(string $validateName, array $data, array $params): array
    {
        $validator = BeanFactory::getBean($validateName);
        if (!$validator instanceof ValidatorInterface) {
            throw new ValidatorException(
                sprintf('User validator(%s) must instance of ValidatorInterface', $validateName)
            );
        }

        return $validator->validate($data, $params);
    }
}
