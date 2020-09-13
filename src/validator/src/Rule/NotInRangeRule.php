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
use Swoft\Validator\Annotation\Mapping\NotInRange;
use Swoft\Validator\Contract\RuleInterface;
use Swoft\Validator\Exception\ValidatorException;

/**
 * Class NotInRangeRule
 *
 * @since 2.0
 *
 * @Bean(NotInRange::class)
 */
class NotInRangeRule implements RuleInterface
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
        /* @var NotInRange $item */
        $min   = $item->getMin();
        $max   = $item->getMax();
        $value = $data[$propertyName];
        if ($value < $min || $value > $max) {
            return $data;
        }

        $message = $item->getMessage();
        $message = (empty($message)) ? sprintf('%s is exists in range(min=%d, max=%d)', $propertyName, $min, $max) :
            $message;

        throw new ValidatorException($message);
    }
}
