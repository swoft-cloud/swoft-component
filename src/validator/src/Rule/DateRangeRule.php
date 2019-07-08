<?php declare(strict_types=1);

namespace Swoft\Validator\Rule;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Validator\Annotation\Mapping\DateRange;
use Swoft\Validator\Contract\RuleInterface;
use Swoft\Validator\Exception\ValidatorException;

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
     * @param array $data
     * @param string $propertyName
     * @param object $item
     * @param null $default
     *
     * @return array
     * @throws ValidatorException
     */
    public function validate(array $data, string $propertyName, $item, $default = null): array
    {
        /* @var DateRange $item */
        $start = $item->getStart();
        $end = $item->getEnd();
        $value = $data[$propertyName];

        if (strtotime($value) >= strtotime($start) && strtotime($value) <= strtotime($end)) {
            return $data;
        }

        $message = $item->getMessage();
        $message = (empty($message)) ? sprintf('%s is invalid  date range(start=%s, end=%s)', $propertyName, $start,
            $end) : $message;

        throw new ValidatorException($message);
    }

}
