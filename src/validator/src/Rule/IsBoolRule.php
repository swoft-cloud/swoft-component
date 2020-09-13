<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Validator\Rule;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Validator\Annotation\Mapping\IsBool;
use Swoft\Validator\Contract\RuleInterface;
use Swoft\Validator\Exception\ValidatorException;

/**
 * Class IsBoolRule
 *
 * @since 2.0
 *
 * @Bean(IsBool::class)
 */
class IsBoolRule implements RuleInterface
{
    /**
     * @param array      $data
     * @param string     $propertyName
     * @param object     $item
     * @param mixed|null $default
     *
     * @return array
     * @throws ValidatorException
     */
    public function validate(array $data, string $propertyName, $item, $default = null, $strict = false): array
    {
        /* @var IsBool $item */
        $message = $item->getMessage();
        if (!isset($data[$propertyName]) && $default !== null) {
            $data[$propertyName] = (bool)$default;

            return $data;
        }

        if (!isset($data[$propertyName]) && $default === null) {
            $message = (empty($message)) ? sprintf('%s must exist!', $propertyName) : $message;
            throw new ValidatorException($message);
        }

        $value = filter_var($data[$propertyName], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        if (is_bool($value)) {
            $data[$propertyName] = $value;
            return $data;
        }

        $message = (empty($message)) ? sprintf('%s must bool!', $propertyName) : $message;
        throw new ValidatorException($message);
    }
}
