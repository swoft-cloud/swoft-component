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
use SwoftTest\Validator\Testing\ValidateDemo2;

/**
 * Class ValidatorTest
 *
 * @since 2.0
 */
class ValidatorTest extends TestCase
{
    public function testTypeEmail(): void
    {
        $data = [];
        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('email must exist!');
        (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo2::class, 'testEmail'));
    }

    public function testFailEmail(): void
    {
        $data = [
            'email' => 'swoft'
        ];
        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('email messsage');
        (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo2::class, 'testEmail'));
    }

    public function testFailEmail2(): void
    {
        $data = [
            'email' => 'swoft'
        ];

        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('email messsage');
        (new Validator())->validate($data, 'testDefaultValidator', ['email']);
    }

    /**
     * @throws ValidatorException
     */
    public function testEmail(): void
    {
        $data = [
            'email' => 'swoft@swoft.org'
        ];
        [$result] = (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo2::class, 'testEmail'));
        $this->assertEquals($result, $data);
    }

    /**
     * @throws ValidatorException
     */
    public function testEmail2(): void
    {
        $data   = [
            'email' => 'swoft@swoft.org'
        ];
        $result = (new Validator())->validate($data, 'testDefaultValidator', ['email']);
        $this->assertEquals($result, $data);
    }

    public function testFailEnum(): void
    {
        $data = [
            'enum' => 1,
        ];
        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('enum message');
        (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo2::class, 'testEnum'));
    }

    /**
     * @throws ValidatorException
     */
    public function testEnum(): void
    {
        $data = [
            'enum' => 4,
        ];
        [$result] = (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo2::class, 'testEnum'));

        $this->assertEquals($result, $data);
    }

    public function testFailIp(): void
    {
        $data = [
            'ip' => '11',
        ];
        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('ip message');
        (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo2::class, 'testIp'));
    }

    public function testIp(): void
    {
        $data = [
            'ip' => '127.0.0.1',
        ];
        [$result] = (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo2::class, 'testIp'));

        $this->assertEquals($result, $data);
    }

    public function testFailLength(): void
    {
        $data = [
            'length' => '1',
        ];

        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('length message');
        (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo2::class, 'testLength'));
    }

    /**
     * @throws ValidatorException
     */
    public function testLength(): void
    {
        $data = [
            'length' => '12121',
        ];
        [$result] = (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo2::class, 'testLength'));

        $this->assertEquals($result, $data);
    }

    public function testFailMax(): void
    {
        $data = [
            'max' => 18,
        ];

        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('max message');

        (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo2::class, 'testMax'));
    }

    /**
     * @throws ValidatorException
     */
    public function testMax(): void
    {
        $data = [
            'max' => 12,
        ];
        [$result] = (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo2::class, 'testMax'));

        $this->assertEquals($result, $data);
    }

    public function testFailMin(): void
    {
        $data = [
            'min' => 0,
        ];

        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('min message');

        (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo2::class, 'testMin'));
    }

    /**
     * @throws ValidatorException
     */
    public function testMin(): void
    {
        $data = [
            'min' => 2,
        ];
        [$result] = (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo2::class, 'testMin'));

        $this->assertEquals($result, $data);
    }

    public function testFailMobile(): void
    {
        $data = [
            'mobile' => '13442',
        ];

        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('mobile message');

        (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo2::class, 'testMobile'));
    }

    /**
     * @throws ValidatorException
     */
    public function testMobile(): void
    {
        $data = [
            'mobile' => '13511111111',
        ];
        [$result] = (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo2::class, 'testMobile'));

        $this->assertEquals($result, $data);
    }

    public function testFailNotEmpty(): void
    {
        $data = [
            'notEmpty' => '',
        ];

        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('not empty message');

        (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo2::class, 'testNotEmpty'));
    }

    /**
     * @throws ValidatorException
     */
    public function testNotEmpty(): void
    {
        $data = [
            'notEmpty' => '121',
        ];
        [$result] = (new Validator())->validateRequest(
            $data,
            $this->getValidates(ValidateDemo2::class, 'testNotEmpty')
        );

        $this->assertEquals($result, $data);
    }

    public function testFailPattern(): void
    {
        $data = [
            'pattern' => 'swift',
        ];

        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('pattern message');

        (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo2::class, 'testPattern'));
    }

    /**
     * @throws ValidatorException
     */
    public function testPattern(): void
    {
        $data = [
            'pattern' => 'swoft',
        ];
        [$result] = (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo2::class, 'testPattern'));

        $this->assertEquals($result, $data);
    }

    public function testFailRange(): void
    {
        $data = [
            'range' => 100,
        ];

        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('range message');

        (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo2::class, 'testRange'));
    }

    public function testFailRange2(): void
    {
        $data = [
            'range' => 100,
        ];

        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('range message');

        (new Validator())->validate($data, 'testDefaultValidator', ['range']);
    }

    /**
     * @throws ValidatorException
     */
    public function testRange(): void
    {
        $data = [
            'range' => 99,
        ];
        [$result] = (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo2::class, 'testRange'));

        $this->assertEquals($result, $data);
    }

    /**
     * @throws ValidatorException
     */
    public function testRange2(): void
    {
        $data = [
            'range' => 99,
        ];
        $result = (new Validator())->validate($data, 'testDefaultValidator', ['range']);

        $this->assertEquals($result, $data);
    }
}
