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

    /**
     * @expectedException Swoft\Validator\Exception\ValidatorException
     *
     * @throws ValidatorException
     */
    public function testUnfieldsException(): void
    {
        $data = [];
        (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo3::class, 'unfield2'));
    }

    /**
     * @expectedException Swoft\Validator\Exception\ValidatorException
     * @expectedExceptionMessage email must be a email
     *
     * @throws ValidatorException
     */
    public function testUnfieldsException2(): void
    {
        $data = [
            'email' => '121',
        ];
        (new Validator())->validate($data, TestValidator3::class, [], [], ['ip', 'count']);
    }
}
