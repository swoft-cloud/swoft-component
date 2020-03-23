<?php declare(strict_types=1);

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
