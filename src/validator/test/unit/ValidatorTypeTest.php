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

use Swoft\Validator\Validator;
use SwoftTest\Validator\Testing\ValidateDemo;
use Swoft\Validator\Exception\ValidatorException;

/**
 * Class ValidatorTypeTest
 *
 * @since 2.0
 */
class ValidatorTypeTest extends TestCase
{
    public function testArrayType(): void
    {
        $this->expectExceptionMessage('array must exist!');
        $this->expectException(ValidatorException::class);
        $data = [];
        (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo::class, 'testArray'));
    }

    public function testIntType(): void
    {
        $data = [];

        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('int must exist!');

        (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo::class, 'testInt'));
    }

    public function testBoolType(): void
    {
        $data = [];

        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('bool must exist!');

        (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo::class, 'testBool'));
    }

    public function testStringType(): void
    {
        $data = [];

        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('string must exist!');

        (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo::class, 'testString'));
    }

    public function testFloatType(): void
    {
        $data = [];

        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('float must exist!');

        (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo::class, 'testFloat'));
    }

    public function testArrayTypeMessage(): void
    {
        $data = [];

        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('array message');

        (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo::class, 'testArrayMessage'));
    }

    public function testIntTypeMessage(): void
    {
        $data = [];
        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('int message');

        (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo::class, 'testIntMessage'));
    }

    public function testBoolTypeMessage(): void
    {
        $data = [];
        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('bool message');

        (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo::class, 'testBoolMessage'));
    }

    public function testStringTypeMessage(): void
    {
        $data = [];
        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('string message');

        (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo::class, 'testStringMessage'));
    }

    public function testFloatTypeMessage(): void
    {
        $data = [];
        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('float message');

        (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo::class, 'testFloatMessage'));
    }

    /**
     * @throws ValidatorException
     */
    public function testDefault(): void
    {
        $data = [];
        [$data] = (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo::class, 'testTypeDefault'));

        $result = [
            'arrayDefault'  => [],
            'stringDefault' => '',
            'intDefault'    => 6,
            'boolDefault'   => false,
            'floatDefault'  => 1.0,
        ];
        $this->assertEquals($data, $result);
    }

    /**
     * @throws ValidatorException
     */
    public function testName(): void
    {
        $data = [];
        $validates = $this->getValidates(ValidateDemo::class, 'testName');
        [$result] = (new Validator())->validateRequest($data, $validates);
        $this->assertEquals(['swoftName' => 'swoft'], $result);
    }

    public function testFailName(): void
    {
        $data = [
            'swoftName' => 12
        ];
        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('swoftName must string!');

        (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo::class, 'testName'));
    }

    public function testIntTypeQuery(): void
    {
        $data = [
            'int' => 1,
        ];
        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('int must exist!');

        (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo::class, 'testIntQuery'));
    }

    public function testBoolTypeQuery(): void
    {
        $data = [
            'bool' => false
        ];
        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('bool must exist!');

        (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo::class, 'testBoolQuery'));
    }

    public function testStringTypeQuery(): void
    {
        $data = [
            'string' => 'string'
        ];
        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('string must exist!');

        (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo::class, 'testStringQuery'));
    }

    public function testFloatTypeQuery(): void
    {
        $data = [
            'float' => 1.1
        ];

        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('float must exist!');

        (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo::class, 'testFloatQuery'));
    }

    /**
     * @throws ValidatorException
     */
    public function testFloatTypeQuery2(): void
    {
        $query = [
            'float' => '2.2'
        ];
        [, $result] = (new Validator())->validateRequest(
            [],
            $this->getValidates(
            ValidateDemo::class,
            'testFloatQuery'
        ),
            $query
        );

        $this->assertEquals($result, ['float' => 2.2]);
    }
}
