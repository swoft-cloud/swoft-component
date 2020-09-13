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
use Swoft\Validator\Annotation\Mapping\Email;
use Swoft\Validator\Contract\RuleInterface;
use Swoft\Validator\Exception\ValidatorException;
use Swoft\Validator\Helper\ValidatorHelper;

/**
 * Class EmailRule
 *
 * @since 2.0
 *
 * @Bean(Email::class)
 */
class EmailRule implements RuleInterface
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
        $value = $data[$propertyName];
        if (ValidatorHelper::validateEmail($value)) {
            return $data;
        }

        /* @var Email $item */
        $message = $item->getMessage();
        $message = (empty($message)) ? sprintf('%s must be a email', $propertyName) : $message;

        throw new ValidatorException($message);
    }
}
