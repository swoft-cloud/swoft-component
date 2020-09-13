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
use SwoftTest\Validator\Testing\Validator\NoRequired;

/**
 * Class ValidatorNoRequired
 *
 * @since 2.0
 *
 * @Bean()
 */
class ValidatorNoRequired
{
    /**
     * @Validate(validator=NoRequired::class)
     *
     * @return bool
     */
    public function testNoRequired(): bool
    {
        return true;
    }
}
