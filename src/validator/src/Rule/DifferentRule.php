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
use Swoft\Validator\Annotation\Mapping\Different;
use Swoft\Validator\Contract\RuleInterface;
use Swoft\Validator\Exception\ValidatorException;

/**
 * Class DifferentRule
 *
 * @since 2.0
 *
 * @Bean(Different::class)
 */
class DifferentRule implements RuleInterface
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
        /* @var Different $item */
        $name  = $data[$item->getName()] ?? '';
        $value = $data[$propertyName];
        if ((string)$value !== (string)$name) {
            return $data;
        }
        $message = $item->getMessage();
        $message = (empty($message)) ? sprintf('%s must be different %s field', $propertyName, $item->getName()) :
            $message;
        throw new ValidatorException($message);
    }
}
