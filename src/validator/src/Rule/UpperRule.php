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
use Swoft\Validator\Annotation\Mapping\Upper;
use Swoft\Validator\Contract\RuleInterface;
use Swoft\Validator\Exception\ValidatorException;

/**
 * Class UpperRule
 *
 * @since 2.0
 *
 * @Bean(Upper::class)
 */
class UpperRule implements RuleInterface
{
    /**
     * @param array  $data
     * @param string $propertyName
     * @param object $item
     * @param null   $default
     *
     * @return array
     * @throws ValidatorException
     */
    public function validate(array $data, string $propertyName, $item, $default = null, $strict = false): array
    {
        $value = $data[$propertyName];
        $rule  = '/^[A-Z]+$/';
        if (preg_match($rule, $value)) {
            return $data;
        }

        /* @var Upper $item */
        $message = $item->getMessage();
        $message = (empty($message)) ? sprintf('%s must be uppercase alpha', $propertyName) : $message;

        throw new ValidatorException($message);
    }
}
