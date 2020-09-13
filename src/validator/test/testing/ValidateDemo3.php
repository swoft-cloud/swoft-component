<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Validator\Testing;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Validator\Annotation\Mapping\Validate;
use SwoftTest\Validator\Testing\Validator\TestValidator3;

/**
 * Class ValidateDemo3
 *
 * @since 2.0
 *
 * @Bean()
 */
class ValidateDemo3
{
    /**
     * @Validate(validator=TestValidator3::class, unfields={"ip", "count", "email"})
     *
     * @return bool
     */
    public function unfield(): bool
    {
        return true;
    }

    /**
     * @Validate(validator=TestValidator3::class, unfields={"ip", "count"})
     *
     * @return bool
     */
    public function unfield2(): bool
    {
        return true;
    }
}
