<?php declare(strict_types=1);

namespace Swoft\Validator\Rule;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Validator\Annotation\Mapping\BeforeDate;
use Swoft\Validator\Contract\RuleInterface;
use Swoft\Validator\Exception\ValidatorException;

/**
 * Class BeforeDateRule
 *
 * @since 2.0
 *
 * @Bean(BeforeDate::class)
 */
class BeforeDateRule implements RuleInterface
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
        /* @var BeforeDate $item */
        $date = $item->getDate();
        $value = $data[$propertyName];
        if (strtotime($value) <= strtotime($date)) {
            return $data;
        }

        $message = $item->getMessage();
        $message = (empty($message)) ? sprintf('%s must be before %s', $propertyName, $date) : $message;

        throw new ValidatorException($message);
    }
}
