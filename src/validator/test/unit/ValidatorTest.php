<?php declare(strict_types=1);


namespace SwoftTest\Validator\Unit;


use PHPUnit\Framework\TestCase;
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
    /**
     * @expectedException Swoft\Validator\Exception\ValidatorException
     * @expectedExceptionMessage email must exist!
     *
     * @throws ValidatorException
     */
    public function testTypeEmail()
    {
        $data = [];
        Validator::validate($data, ValidateDemo2::class, 'testEmail');
    }

    /**
     * @expectedException Swoft\Validator\Exception\ValidatorException
     * @expectedExceptionMessage email messsage
     *
     * @throws ValidatorException
     */
    public function testFailEmail()
    {
        $data = [
            'email' => 'swoft'
        ];
        Validator::validate($data, ValidateDemo2::class, 'testEmail');
    }

    /**
     * @throws ValidatorException
     */
    public function testEmail()
    {
        $data   = [
            'email' => 'swoft@swoft.org'
        ];
        $result = Validator::validate($data, ValidateDemo2::class, 'testEmail');
        $this->assertEquals($result, $data);
    }

    /**
     * @expectedException Swoft\Validator\Exception\ValidatorException
     * @expectedExceptionMessage enum message
     *
     * @throws ValidatorException
     */
    public function testFailEnum()
    {
        $data = [
            'enum' => 1,
        ];
        Validator::validate($data, ValidateDemo2::class, 'testEnum');
    }

    /**
     * @throws ValidatorException
     */
    public function testEnum()
    {
        $data   = [
            'enum' => 4,
        ];
        $result = Validator::validate($data, ValidateDemo2::class, 'testEnum');

        $this->assertEquals($result, $data);
    }

    /**
     * @expectedException Swoft\Validator\Exception\ValidatorException
     * @expectedExceptionMessage ip message
     *
     * @throws ValidatorException
     */
    public function testFailIp()
    {
        $data = [
            'ip' => '11',
        ];
        Validator::validate($data, ValidateDemo2::class, 'testIp');
    }

    /**
     * @throws ValidatorException
     */
    public function testIp()
    {
        $data   = [
            'ip' => '127.0.0.1',
        ];
        $result = Validator::validate($data, ValidateDemo2::class, 'testIp');

        $this->assertEquals($result, $data);
    }

    /**
     * @expectedException Swoft\Validator\Exception\ValidatorException
     * @expectedExceptionMessage length message
     *
     * @throws ValidatorException
     */
    public function testFailLength()
    {
        $data = [
            'length' => '1',
        ];
        Validator::validate($data, ValidateDemo2::class, 'testLength');
    }

    /**
     * @throws ValidatorException
     */
    public function testLength()
    {
        $data   = [
            'length' => '12121',
        ];
        $result = Validator::validate($data, ValidateDemo2::class, 'testLength');

        $this->assertEquals($result, $data);
    }

    /**
     * @expectedException Swoft\Validator\Exception\ValidatorException
     * @expectedExceptionMessage max message
     *
     * @throws ValidatorException
     */
    public function testFailMax()
    {
        $data = [
            'max' => 18,
        ];
        Validator::validate($data, ValidateDemo2::class, 'testMax');
    }

    /**
     * @throws ValidatorException
     */
    public function testMax()
    {
        $data   = [
            'max' => 12,
        ];
        $result = Validator::validate($data, ValidateDemo2::class, 'testMax');

        $this->assertEquals($result, $data);
    }

    /**
     * @expectedException Swoft\Validator\Exception\ValidatorException
     * @expectedExceptionMessage min message
     *
     * @throws ValidatorException
     */
    public function testFailMin()
    {
        $data = [
            'min' => 0,
        ];
        Validator::validate($data, ValidateDemo2::class, 'testMin');
    }

    /**
     * @throws ValidatorException
     */
    public function testMin()
    {
        $data   = [
            'min' => 2,
        ];
        $result = Validator::validate($data, ValidateDemo2::class, 'testMin');

        $this->assertEquals($result, $data);
    }

    /**
     * @expectedException Swoft\Validator\Exception\ValidatorException
     * @expectedExceptionMessage mobile message
     *
     * @throws ValidatorException
     */
    public function testFailMobile()
    {
        $data = [
            'mobile' => '13442',
        ];
        Validator::validate($data, ValidateDemo2::class, 'testMobile');
    }

    /**
     * @throws ValidatorException
     */
    public function testMobile()
    {
        $data   = [
            'mobile' => '13511111111',
        ];
        $result = Validator::validate($data, ValidateDemo2::class, 'testMobile');

        $this->assertEquals($result, $data);
    }

    /**
     * @expectedException Swoft\Validator\Exception\ValidatorException
     * @expectedExceptionMessage not empty message
     *
     * @throws ValidatorException
     */
    public function testFailNotEmpty()
    {
        $data = [
            'notEmpty' => '',
        ];
        Validator::validate($data, ValidateDemo2::class, 'testNotEmpty');
    }

    /**
     * @throws ValidatorException
     */
    public function testNotEmpty()
    {
        $data   = [
            'notEmpty' => '121',
        ];
        $result = Validator::validate($data, ValidateDemo2::class, 'testNotEmpty');

        $this->assertEquals($result, $data);
    }

    /**
     * @expectedException Swoft\Validator\Exception\ValidatorException
     * @expectedExceptionMessage pattern message
     *
     * @throws ValidatorException
     */
    public function testFailPattern()
    {
        $data = [
            'pattern' => 'swift',
        ];
        Validator::validate($data, ValidateDemo2::class, 'testPattern');
    }

    /**
     * @throws ValidatorException
     */
    public function testPattern()
    {
        $data   = [
            'pattern' => 'swoft',
        ];
        $result = Validator::validate($data, ValidateDemo2::class, 'testPattern');

        $this->assertEquals($result, $data);
    }

    /**
     * @expectedException Swoft\Validator\Exception\ValidatorException
     * @expectedExceptionMessage range message
     *
     * @throws ValidatorException
     */
    public function testFailRange()
    {
        $data = [
            'range' => 100,
        ];
        Validator::validate($data, ValidateDemo2::class, 'testRange');
    }

    /**
     * @throws ValidatorException
     */
    public function testRange()
    {
        $data   = [
            'range' => 99,
        ];
        $result = Validator::validate($data, ValidateDemo2::class, 'testRange');

        $this->assertEquals($result, $data);
    }
}