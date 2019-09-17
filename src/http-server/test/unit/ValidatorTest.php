<?php declare(strict_types=1);


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
class ValidatorTest extends TestCase
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
    public function testUserValidator()
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
    public function testUserValidator2()
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
    public function testDefaultValidatorQuery()
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
    public function testFailUserValidatorQuery()
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
    public function testUserValidatorQuery()
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
    public function testNoToValidate()
    {
        $content = 'swoft framework';
        $ext     = [
            'content' => $content
        ];

        $response = $this->mockServer->request(MockRequest::POST, '/testValidator/noToValidate', [], [], [], $ext);
        $response->assertEqualContent(json_encode([$content]));
    }
}
