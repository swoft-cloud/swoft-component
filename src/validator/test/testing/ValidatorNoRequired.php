<?php declare(strict_types=1);

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
