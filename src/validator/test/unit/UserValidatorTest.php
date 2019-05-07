<?php declare(strict_types=1);


namespace SwoftTest\Validator\Unit;


use PHPUnit\Framework\TestCase;
use Swoft\Validator\Exception\ValidatorException;
use Swoft\Validator\Validator;
use SwoftTest\Validator\Testing\ValidateUser;

/**
 * Class UserValidatorTest
 *
 * @since 2.0
 */
class UserValidatorTest extends TestCase
{

    /**
     * @expectedException Swoft\Validator\Exception\ValidatorException
     * @expectedExceptionMessage Start(fb5566f7d8580b4162f38d3c232582ae) must less than end
     *
     * @throws ValidatorException
     */
    public function testUserFail()
    {
        $data = [
            'start' => 123,
            'end' => 121
        ];
        (new Validator())->validate($data, ValidateUser::class, 'testUser');
    }

    public function testFail()
    {
        $data = [
            'start' => 123,
            'end' => 126
        ];
        $result = (new Validator())->validate($data, ValidateUser::class, 'testUser');
        $this->assertEquals($data, $result);
    }
}