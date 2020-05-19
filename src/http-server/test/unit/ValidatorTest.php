<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Http\Server\Unit;

use Swoft\Exception\SwoftException;
use Swoft\Validator\Exception\ValidatorException;
use SwoftTest\Http\Server\Testing\MockRequest;
use SwoftTest\Http\Server\Testing\Validator\UserBaseValidate;

/**
 * Class ValidatorTest
 *
 * @since 2.0
 */
class ValidatorTest extends HttpServerTestCase
{
    /**
     * @throws SwoftException
     */
    public function testDefaultValidator(): void
    {
        $data     = [
            'string'  => 'string',
            'int'     => 1,
            'float'   => 1.2,
            'bool'    => true,
            'array'   => [
                'array'
            ],
            'kString' => 'string',
            'noKey'   => 'not',
        ];
        $response = $this->mockServer->request(MockRequest::POST, '/testValidator/defautValidator');
        $response->assertEqualJson($data);
    }

    /**
     * @throws SwoftException
     */
    public function testFailUserValidator(): void
    {
        $data     = [
            'start' => 12,
            'end'   => 10
        ];
        $response = $this->mockServer->request(MockRequest::POST, '/testValidator/userValidator', $data);
        $response->assertContainContent('Start(f79408e5ca998cd53faf44af31e6eb45) must less than end');
    }

    /**
     * @throws SwoftException
     */
    public function testUserValidator(): void
    {
        $data     = [
            'start'  => 12,
            'end'    => 16,
            'params' => [1, 2]
        ];
        $response = $this->mockServer->request(MockRequest::POST, '/testValidator/userValidator', $data);
        $response->assertEqualJson($data);
    }

    /**
     * @throws ValidatorException
     */
    public function testUserValidator2(): void
    {
        $data = [
            'start'  => 12,
            'end'    => 16,
            'params' => [1, 2]
        ];

        $users = [
            'testUserValidtor' => [1, 2]
        ];

        $result = validate($data, UserBaseValidate::class, [], $users);
        $this->assertEquals($data, $result);
    }

    /**
     * @throws SwoftException
     */
    public function testDefaultValidatorQuery(): void
    {
        $data     = [
            'string'  => 'string',
            'int'     => 1,
            'float'   => 1.2,
            'bool'    => true,
            'array'   => [
                'array'
            ],
            'kString' => 'string',
            'noKey'   => 'not',
        ];
        $response = $this->mockServer->request(MockRequest::GET, '/testValidator/defaultValidatorQuery');
        $response->assertEqualJson($data);
    }

    /**
     * @throws SwoftException
     */
    public function testFailUserValidatorQuery(): void
    {
        $data     = [
            'start' => 12,
            'end'   => 10
        ];
        $response = $this->mockServer->request(MockRequest::GET, '/testValidator/userValidatorQuery', $data);
        $response->assertContainContent('Start(f79408e5ca998cd53faf44af31e6eb45) must less than end');
    }

    /**
     * @throws SwoftException
     */
    public function testUserValidatorQuery(): void
    {
        $data     = [
            'start'  => 12,
            'end'    => 16,
            'params' => [1, 2]
        ];
        $response = $this->mockServer->request(MockRequest::GET, '/testValidator/userValidatorQuery', $data);
        $response->assertEqualJson($data);
    }

    /**
     * @throws SwoftException
     */
    public function testNoToValidate(): void
    {
        $content = 'swoft framework';
        $ext     = [
            'content' => $content
        ];

        $response = $this->mockServer->request(MockRequest::POST, '/testValidator/noToValidate', [], [], [], $ext);
        $response->assertEqualContent(json_encode([$content]));
    }
}
