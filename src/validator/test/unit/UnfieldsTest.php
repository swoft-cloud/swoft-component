<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Validator\Unit;

use Swoft\Validator\Exception\ValidatorException;
use Swoft\Validator\Validator;
use SwoftTest\Validator\Testing\ValidateDemo3;
use SwoftTest\Validator\Testing\Validator\TestValidator3;

/**
 * Class UnfieldsTest
 *
 * @since 2.0
 */
class UnfieldsTest extends TestCase
{
    public function testUnfields(): void
    {
        $data = [];
        [$body] = (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo3::class, 'unfield'));

        $this->assertIsArray($body);
        $this->assertEmpty($body);

        $data = [
            'email' => '121',
        ];

        $body = (new Validator())->validate($data, TestValidator3::class, [], [], ['ip', 'count', 'email']);
        $this->assertIsArray($body);
        $this->assertEquals($body, $data);
    }

    public function testUnfieldsException(): void
    {
        $data = [];
        $this->expectException(\Swoft\Validator\Exception\ValidatorException::class);
        (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo3::class, 'unfield2'));
    }

    public function testUnfieldsException2(): void
    {
        $data = [
            'email' => '121',
        ];

        $this->expectException(\Swoft\Validator\Exception\ValidatorException::class);
        $this->expectExceptionMessage('email must be a email');
        (new Validator())->validate($data, TestValidator3::class, [], [], ['ip', 'count']);
    }
}
