<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Rpc\Server\Unit;

use SwoftTest\Rpc\Server\Testing\Lib\DemoInterface;

/**
 * Class RpcTest
 *
 * @since 2.0
 */
class RpcTest extends TestCase
{
    /**
     * @throws \Swoft\Rpc\Exception\RpcException
     */
    public function testGetList(): void
    {
        $list = [
            'name' => 'list',
            'list' => [
                'id'   => 12,
                'type' => 'type2',
                'name' => 'name'
            ]
        ];

        $response = $this->mockRpcServer->call(DemoInterface::class, 'getList', [12, 'type2']);
        $response->assertSuccess();
        $response->assertEqualJsonResult($list);
    }

    /**
     * @throws \Swoft\Rpc\Exception\RpcException
     */
    public function testGetInfo(): void
    {
        $info = [
            'name' => 'info',
            'item' => [
                'id'   => 12,
                'name' => 'name'
            ]
        ];

        $response = $this->mockRpcServer->call(DemoInterface::class, 'getInfo', [12]);
        $response->assertSuccess();
        $response->assertEqualJsonResult($info);
    }

    /**
     * @throws \Swoft\Rpc\Exception\RpcException
     */
    public function testGetDelete(): void
    {
        $response = $this->mockRpcServer->call(DemoInterface::class, 'delete', [12]);
        $response->assertSuccess();
        $response->assertEqualResult(false);

        $response = $this->mockRpcServer->call(DemoInterface::class, 'delete', [122]);
        $response->assertSuccess();
        $response->assertEqualResult(true);
    }

    /**
     * @throws \Swoft\Rpc\Exception\RpcException
     */
    public function testCallErro(): void
    {
        $response = $this->mockRpcServer->call(DemoInterface::class, 'delete', []);
        $response->assertFail();
        $response->assertErrorCode(0);
        $response->assertContainErrorMessage('Too few arguments to function');
    }

    /**
     * @throws \Swoft\Rpc\Exception\RpcException
     */
    public function testReturnNull(): void
    {
        $response = $this->mockRpcServer->call(DemoInterface::class, 'returnNull', []);
        $response->assertEqualResult(null);
    }

    /**
     * @throws \Swoft\Rpc\Exception\RpcException
     */
    public function testException(): void
    {
        $response = $this->mockRpcServer->call(DemoInterface::class, 'error', []);
        $response->assertFail();
        $response->assertErrorCode(324231);
        $response->assertErrorMessage('error message');
    }
}
