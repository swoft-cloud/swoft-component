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

class RpcMdTest extends TestCase
{
    /**
     * @throws \Swoft\Rpc\Exception\RpcException
     */
    public function testAllMd(): void
    {
        $data     = [
            'name'      => 'list',
            'MethodMd2' => 'MethodMd2',
            'MethodMd3' => 'MethodMd3',
            'MethodMd'  => 'MethodMd',
            'ClassMd2'  => 'ClassMd2',
            'ClassMd3'  => 'ClassMd3',
            'ClassMd'   => 'ClassMd',
            'userMd'    => 'userMd'
        ];
        $response = $this->mockRpcServer->call(DemoInterface::class, 'getList', [12, 'type2'], [], '1.3');
        $this->assertEquals($data, $response->getData());
    }

    /**
     * @throws \Swoft\Rpc\Exception\RpcException
     */
    public function testOneMd(): void
    {
        $data     = [
            'name'      => 'info',
            'MethodMd2' => 'MethodMd2',
            'ClassMd2'  => 'ClassMd2',
            'ClassMd3'  => 'ClassMd3',
            'ClassMd'   => 'ClassMd',
            'userMd'    => 'userMd'
        ];
        $response = $this->mockRpcServer->call(DemoInterface::class, 'getInfo', [12], [], '1.3');
        $this->assertEquals($data, $response->getData());
    }

    /**
     * @throws \Swoft\Rpc\Exception\RpcException
     */
    public function testNotClassMd(): void
    {
        $data = [
            'name'     => 'notClassMd',
            'ClassMd2' => 'ClassMd2',
            'ClassMd3' => 'ClassMd3',
            'ClassMd'  => 'ClassMd',
            'userMd'   => 'userMd'
        ];

        $response = $this->mockRpcServer->call(DemoInterface::class, 'notClassMd', [12], [], '1.3');
        $this->assertEquals($data, $response->getData());
    }
}
