<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Validator\Contract;

/**
 * Class RuleInterface
 *
 * @since 2.0
 */
interface RuleInterface
{
    /**
     * @param array  $data
     * @param string $propertyName
     * @param object $item
     * @param mixed  $default
     *
     * @return array
     */
    public function validate(array $data, string $propertyName, $item, $default = null, $strict = false): array;
}
