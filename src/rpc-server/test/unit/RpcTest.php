<?php declare(strict_types=1);


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
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Rpc\Exception\RpcException
     */
    public function testGetList()
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
}