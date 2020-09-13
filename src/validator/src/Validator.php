<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Validator;

use Swoft;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\BeanFactory;
use Swoft\Validator\Annotation\Mapping\IsBool;
use Swoft\Validator\Annotation\Mapping\IsFloat;
use Swoft\Validator\Annotation\Mapping\IsInt;
use Swoft\Validator\Annotation\Mapping\IsString;
use Swoft\Validator\Annotation\Mapping\Required;
use Swoft\Validator\Annotation\Mapping\ValidateType;
use Swoft\Validator\Contract\RuleInterface;
use Swoft\Validator\Contract\ValidatorInterface;
use Swoft\Validator\Exception\ValidatorException;
use function get_class;
use function in_array;
use function sprintf;
use function strlen;
use function strpos;
use function substr;

/**
 * Class Validator
 *
 * @since 2.0
 *
 * @Bean(name="validator")
 */
class Validator
{
    /**
     * Strict Model
     *
     * @var bool
     */
    protected $strict = false;

    /***
     * @param array  $data
     * @param string $validatorName
     * @param array  $fields
     * @param array  $userValidators
     *
     * @param array  $unfields
     *
     * @return array
     * @throws ValidatorException
     */
    public function validate(
        array $data,
        string $validatorName,
        array $fields = [],
        array $userValidators = [],
        array $unfields = []
    ): array {
        if (empty($data)) {
            throw new ValidatorException('Validator data is empty!');
        }

        $type      = ValidatorRegister::TYPE_DEFAULT;
        $validator = ValidatorRegister::getValidator($validatorName);

        if (empty($validator)) {
            throw new ValidatorException(sprintf('Validator(%s) is not exist!', $validatorName));
        }

        $data = $this->doValidate($data, $type, $validatorName, [], $validator, $fields, $unfields);
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
            $type = (int)$validator['type'];
            if ($type !== ValidatorRegister::TYPE_USER) {
                throw new ValidatorException(sprintf('Validator(%s) is user validator!', $userValidator));
            }

            $data = $this->doValidate($data, $type, $userValidator, $params, $validator, $fields, $unfields);
        }

        return $data;
    }

    /**
     * @param array $body
     * @param array $validates
     * @param array $query
     * @param array $path
     *
     * @return array
     * @throws ValidatorException
     */
    public function validateRequest(array $body, array $validates, array $query = [], array $path = []): array
    {
        foreach ($validates as $name => $validate) {
            $validator = ValidatorRegister::getValidator($name);

            if (empty($validator)) {
                throw new ValidatorException(sprintf('Validator(%s) is not exist!', $name));
            }

            $type     = $validator['type'];
            $fields   = $validate['fields'] ?? [];
            $unfields = $validate['unfields'] ?? [];
            $params   = $validate['params'] ?? [];

            $validateType = $validate['type'];

            // Get query params
            if ($validateType === ValidateType::GET) {
                $query = $this->doValidate($query, $type, $name, $params, $validator, $fields, $unfields);
                continue;
            }

            // Route path params
            if ($validateType === ValidateType::PATH) {
                $path = $this->doValidate($path, $type, $name, $params, $validator, $fields, $unfields);
                continue;
            }

            $body = $this->doValidate($body, $type, $name, $params, $validator, $fields, $unfields);
        }

        return [$body, $query, $path];
    }

    /**
     * @param array  $data
     * @param int    $type
     * @param string $validateName
     * @param array  $params
     * @param array  $validator
     * @param array  $fields
     *
     * @param array  $unfields
     *
     * @return array
     * @throws ValidatorException
     */
    protected function doValidate(
        array $data,
        int $type,
        string $validateName,
        array $params,
        array $validator,
        array $fields,
        array $unfields = []
    ): array {
        // User validator
        if ($type === ValidatorRegister::TYPE_USER) {
            return $this->validateUserValidator($validateName, $data, $params);
        }

        return $this->validateDefaultValidator($data, $validator, $fields, $unfields);
    }

    /**
     * @param array $data
     * @param array $validator
     * @param array $fields
     *
     * @param array $unfields
     *
     * @return array
     */
    protected function validateDefaultValidator(array $data, array $validator, array $fields, array $unfields): array
    {
        $properties = $validator['properties'] ?? [];

        foreach ($properties as $propName => $property) {
            /* @var IsString|IsInt|IsBool|IsFloat $type */
            $type = $property['type']['annotation'] ?? null;
            if ($type === null) {
                continue;
            }

            if ($fields && !in_array($propName, $fields, true)) {
                continue;
            }

            // Un-fields - exclude validate
            if (in_array($propName, $unfields, true)) {
                continue;
            }

            $propName = $type->getName() ?: $propName;
            if (!isset($data[$propName]) && !$property['required'] && !isset($property['type']['default'])) {
                continue;
            }

            $defaultVal  = $property['type']['default'] ?? null;
            $annotations = $property['annotations'] ?? [];

            // Default validate item(Type) and other item
            $data = $this->validateDefaultItem($data, $propName, $type, $defaultVal);
            foreach ($annotations as $annotation) {
                if ($annotation instanceof Required) {
                    continue;
                }
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
     */
    protected function validateDefaultItem(array $data, string $propName, $item, $default = null): array
    {
        $itemClass = get_class($item);

        //support i18n
        $msg    = $item->getMessage();
        $msgLen = strlen($msg) - 1;
        if (strpos($msg, '{') === 0 && strrpos($msg, '}') === $msgLen) {
            $item->setMessage(Swoft::t(substr($msg, 1, -1)));
        }
        /* @var RuleInterface $rule */
        $rule = BeanFactory::getBean($itemClass);
        $data = $rule->validate($data, $propName, $item, $default, $this->strict);
        return $data;
    }

    /**
     * @param string $validateName
     * @param array  $data
     * @param array  $params
     *
     * @return array
     * @throws ValidatorException
     */
    protected function validateUserValidator(string $validateName, array $data, array $params): array
    {
        $validator = BeanFactory::getBean($validateName);
        if (!$validator instanceof ValidatorInterface) {
            throw new ValidatorException(sprintf(
                'User validator(%s) must instance of ValidatorInterface',
                $validateName
            ));
        }

        return $validator->validate($data, $params);
    }
}
