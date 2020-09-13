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

    /**
     * @expectedException Swoft\Validator\Exception\ValidatorException
     * @expectedExceptionMessage int must exist!
     *
     * @throws ValidatorException
     */
    public function testIntType(): void
    {
        $data = [];
        (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo::class, 'testInt'));
    }

    /**
     * @expectedException Swoft\Validator\Exception\ValidatorException
     * @expectedExceptionMessage bool must exist!
     *
     * @throws ValidatorException
     */
    public function testBoolType(): void
    {
        $data = [];
        (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo::class, 'testBool'));
    }

    /**
     * @expectedException Swoft\Validator\Exception\ValidatorException
     * @expectedExceptionMessage string must exist!
     *
     * @throws ValidatorException
     */
    public function testStringType(): void
    {
        $data = [];
        (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo::class, 'testString'));
    }

    /**
     * @expectedException Swoft\Validator\Exception\ValidatorException
     * @expectedExceptionMessage float must exist!
     *
     * @throws ValidatorException
     */
    public function testFloatType(): void
    {
        $data = [];
        (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo::class, 'testFloat'));
    }

    /**
     * @expectedException Swoft\Validator\Exception\ValidatorException
     * @expectedExceptionMessage array message
     *
     * @throws ValidatorException
     */
    public function testArrayTypeMessage(): void
    {
        $data = [];
        (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo::class, 'testArrayMessage'));
    }

    /**
     * @expectedException Swoft\Validator\Exception\ValidatorException
     * @expectedExceptionMessage int message
     *
     * @throws ValidatorException
     */
    public function testIntTypeMessage(): void
    {
        $data = [];
        (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo::class, 'testIntMessage'));
    }

    /**
     * @expectedException Swoft\Validator\Exception\ValidatorException
     * @expectedExceptionMessage bool message
     *
     * @throws ValidatorException
     */
    public function testBoolTypeMessage(): void
    {
        $data = [];
        (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo::class, 'testBoolMessage'));
    }

    /**
     * @expectedException Swoft\Validator\Exception\ValidatorException
     * @expectedExceptionMessage string message
     *
     * @throws ValidatorException
     */
    public function testStringTypeMessage(): void
    {
        $data = [];
        (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo::class, 'testStringMessage'));
    }

    /**
     * @expectedException Swoft\Validator\Exception\ValidatorException
     * @expectedExceptionMessage float message
     *
     * @throws ValidatorException
     */
    public function testFloatTypeMessage(): void
    {
        $data = [];
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

    /**
     * @expectedException Swoft\Validator\Exception\ValidatorException
     * @expectedExceptionMessage swoftName must string!
     *
     * @throws ValidatorException
     */
    public function testFailName(): void
    {
        $data = [
            'swoftName' => 12
        ];
        (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo::class, 'testName'));
    }

    /**
     * @expectedException Swoft\Validator\Exception\ValidatorException
     * @expectedExceptionMessage int must exist!
     *
     * @throws ValidatorException
     */
    public function testIntTypeQuery(): void
    {
        $data = [
            'int' => 1,
        ];
        (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo::class, 'testIntQuery'));
    }

    /**
     * @expectedException Swoft\Validator\Exception\ValidatorException
     * @expectedExceptionMessage bool must exist!
     *
     * @throws ValidatorException
     */
    public function testBoolTypeQuery(): void
    {
        $data = [
            'bool' => false
        ];
        (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo::class, 'testBoolQuery'));
    }

    /**
     * @expectedException Swoft\Validator\Exception\ValidatorException
     * @expectedExceptionMessage string must exist!
     *
     * @throws ValidatorException
     */
    public function testStringTypeQuery(): void
    {
        $data = [
            'string' => 'string'
        ];
        (new Validator())->validateRequest($data, $this->getValidates(ValidateDemo::class, 'testStringQuery'));
    }

    /**
     * @expectedException Swoft\Validator\Exception\ValidatorException
     * @expectedExceptionMessage float must exist!
     *
     * @throws ValidatorException
     */
    public function testFloatTypeQuery(): void
    {
        $data = [
            'float' => 1.1
        ];
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
