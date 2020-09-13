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
use SwoftTest\Validator\Testing\ValidateUser;
use SwoftTest\Validator\Testing\Validator\UserBaseValidate;
use SwoftTest\Validator\Testing\Validator\UserValidator;

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
    public function testUserFail(): void
    {
        $data = [
            'start' => 123,
            'end'   => 121
        ];
        (new Validator())->validateRequest($data, $this->getValidates(ValidateUser::class, 'testUser'));
    }

    /**
     * @expectedException Swoft\Validator\Exception\ValidatorException
     * @expectedExceptionMessage Start(fb5566f7d8580b4162f38d3c232582ae) must less than end
     *
     * @throws ValidatorException
     */
    public function testUserFail2(): void
    {
        $data = [
            'start' => 123,
            'end'   => 121
        ];

        $users = [
            UserValidator::class => [
                1,
                'name'
            ]
        ];
        (new Validator())->validate($data, UserBaseValidate::class, [], $users);
    }

    public function testFail(): void
    {
        $data = [
            'start'  => 123,
            'end'    => 126,
            'params' => [1, 'name']
        ];

        [$result] = (new Validator())->validateRequest($data, $this->getValidates(ValidateUser::class, 'testUser'));
        $this->assertEquals($data, $result);
    }

    public function testFail2(): void
    {
        $data = [
            'start'  => 123,
            'end'    => 126,
            'params' => [1, 'name']
        ];

        $users = [
            UserValidator::class => [
                1,
                'name'
            ]
        ];
        $result = (new Validator())->validate($data, UserBaseValidate::class, [], $users);
        $this->assertEquals($data, $result);
    }
}
