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
use SwoftTest\Validator\Testing\Validator\RequiredValidator;

/**
 * Class ValidatorRequired
 *
 * @since 2.0
 *
 * @Bean()
 */
class ValidatorRequired
{
    /**
     * @Validate(RequiredValidator::class)
     *
     * @return bool
     */
    public function testRequired(): bool
    {
        return true;
    }
}
