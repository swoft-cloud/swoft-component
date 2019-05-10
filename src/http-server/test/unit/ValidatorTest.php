<?php declare(strict_types=1);


namespace SwoftTest\Http\Server\Unit;

use Swoft\Bean\Exception\ContainerException;
use Swoft\Test\Http\MockRequest;

/**
 * Class ValidatorTest
 *
 * @since 2.0
 */
class ValidatorTest extends TestCase
{
    /**
     * @throws \ReflectionException
     * @throws ContainerException
     */
    public function testDefaultValidator()
    {
        $data = [
            'string' => 'string',
            'int' => 1,
            'float' => 1.2,
            'bool' => true,
            'array' => [
                'array'
            ]
        ];
        $response = $this->mockServer->request(MockRequest::POST, '/testValidator/defautValidator');
        $response->assertEqualJson($data);
    }

    /**
     * @throws \ReflectionException
     * @throws ContainerException
     */
    public function testFailUserValidator()
    {
        $data = [
            'start' => 12,
            'end' => 10
        ];
        $response = $this->mockServer->request(MockRequest::POST, '/testValidator/userValidator', $data);
        $response->assertContainContent('Start(d751713988987e9331980363e24189ce) must less than end');
    }

    /**
     * @throws \ReflectionException
     * @throws ContainerException
     */
    public function testUserValidator()
    {
        $data     = [
            'start' => 12,
            'end'   => 16
        ];
        $response = $this->mockServer->request(MockRequest::POST, '/testValidator/userValidator', $data);
        $response->assertEqualJson($data);
    }
}