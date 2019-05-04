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
        $data = [
            'email' => 'swoft@swoft.org'
        ];
        $result = Validator::validate($data, ValidateDemo2::class, 'testEmail');
        $this->assertEquals($result, $data);
    }
}