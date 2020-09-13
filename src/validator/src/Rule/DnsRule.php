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
use Swoft\Validator\Annotation\Mapping\Dns;
use Swoft\Validator\Contract\RuleInterface;
use Swoft\Validator\Exception\ValidatorException;

/**
 * Class DnsRule
 *
 * @since 2.0
 *
 * @Bean(Dns::class)
 */
class DnsRule implements RuleInterface
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

        /* @var Dns $item */
        $message = $item->getMessage();
        if (empty($value)) {
            $message = (empty($message)) ? sprintf('%s can not be empty!', $propertyName) : $message;
            throw new ValidatorException($message);
        }
        if (checkdnsrr($value)) {
            return $data;
        }
        $message = (empty($message)) ? sprintf('%s invalid dns', $propertyName) : $message;

        throw new ValidatorException($message);
    }
}
