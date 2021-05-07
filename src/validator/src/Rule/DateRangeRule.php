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
use Swoft\Validator\Annotation\Mapping\DateRange;
use Swoft\Validator\Contract\RuleInterface;
use Swoft\Validator\Exception\ValidatorException;
use function strtotime;

/**
 * Class AlphaRule
 *
 * @since 2.0
 *
 * @Bean(DateRange::class)
 */
class DateRangeRule implements RuleInterface
{
    /**
     * @param array            $data
     * @param string           $propertyName
     * @param object|DateRange $item
     * @param null             $default
     * @param bool             $strict
     *
     * @return array
     */
    public function validate(array $data, string $propertyName, $item, $default = null, $strict = false): array
    {
        $endTs = strtotime($end = $item->getEnd());
        $value = $data[$propertyName];

        $startTs = strtotime($start = $item->getStart());
        if (is_string($value)) {
            // $dt = DateTime::createFromFormat('Y-m-d H:i:s', $value);
            $ts = strtotime($value);
            if ($ts > 0 && $ts >= $startTs && $ts <= $endTs) {
                return $data;
            }

            // is timestamp
            if (ctype_digit($value) && date('Y-m-d', (int)$value) && $value >= $startTs && $value <= $endTs) {
                return $data;
            }
        } elseif (filter_var($value, FILTER_VALIDATE_INT)) {
            if ($value >= $startTs && $value <= $endTs) {
                return $data;
            }
        }

        $message = $item->getMessage();
        $message = $message ?: sprintf('%s is invalid date range(start=%s, end=%s)', $propertyName, $start, $end);

        throw new ValidatorException($message);
    }
}
