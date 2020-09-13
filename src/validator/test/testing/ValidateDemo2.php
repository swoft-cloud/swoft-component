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

/**
 * Class ValidateDemo2
 *
 * @since 2.0
 *
 * @Bean()
 */
class ValidateDemo2
{
    /**
     * @Validate(validator="testDefaultValidator", fields={"email"})
     */
    public function testEmail(): void
    {
    }

    /**
     * @Validate(validator="testDefaultValidator", fields={"enum"})
     */
    public function testEnum(): void
    {
    }

    /**
     * @Validate(validator="testDefaultValidator", fields={"ip"})
     */
    public function testIp(): void
    {
    }

    /**
     * @Validate(validator="testDefaultValidator", fields={"length"})
     */
    public function testLength(): void
    {
    }

    /**
     * @Validate(validator="testDefaultValidator", fields={"max"})
     */
    public function testMax(): void
    {
    }

    /**
     * @Validate(validator="testDefaultValidator", fields={"min"})
     */
    public function testMin(): void
    {
    }

    /**
     * @Validate(validator="testDefaultValidator", fields={"mobile"})
     */
    public function testMobile(): void
    {
    }

    /**
     * @Validate(validator="testDefaultValidator", fields={"notEmpty"})
     */
    public function testNotEmpty(): void
    {
    }

    /**
     * @Validate(validator="testDefaultValidator", fields={"pattern"})
     */
    public function testPattern(): void
    {
    }

    /**
     * @Validate(validator="testDefaultValidator", fields={"range"})
     */
    public function testRange(): void
    {
    }
}
