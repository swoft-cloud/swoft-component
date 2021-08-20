<?php declare(strict_types=1);

namespace SwoftTest\Rpc\Client\Unit;

use Swoft\Rpc\Packet;
use Swoole\Coroutine;
use Swoft\Rpc\Protocol;
use Swoft\Rpc\Client\Pool;
use Swoft\Context\Context;
use Swoft\Rpc\Client\Proxy;
use Swoft\Bean\BeanFactory;
use Swoole\Coroutine\Server;
use Swoft\Rpc\Client\Client;
use SwoftTest\Testing\TestContext;
use Swoole\Coroutine\Server\Connection;
use Swoft\Rpc\Client\ReferenceRegister;
use SwoftTest\Rpc\Server\Testing\MockRpcServer;
use Swoft\Rpc\Client\Annotation\Mapping\Reference;
use Swoft\Rpc\Client\Exception\RpcClientException;
use SwoftTest\Rpc\Client\Testing\Lib\RpcReadOldCallInterface;

class RpcReadOldCallTest extends TestCase
{
    /**
     * @var MockRpcServer
     */
    private static $mockRpcServer;

    /**
     * @Reference("user.pool")
     * @var RpcReadOldCallInterface
     */
    private static $rpcReadOldCallService;

    /**
     * @var Server
     */
    private static $server;

    /**
     * @var Pool
     */
    private static $rpcClientPool;

    /**
     * 初始化服务
     */
    public static function setUpBeforeClass(): void
    {

        // 创建RPC客户端
        $rpcClientDefinition = [
            'class'   => Client::class,
            'host'    => '127.0.0.1',
            'port'    => 18307,
            'setting' => [
                'timeout'         => 1,
                'connect_timeout' => 1,
                'write_timeout'   => 1,
                'read_timeout'    => 1,
            ],
            'packet'  => bean('rpcClientPacket')
        ];

        // 创建RPC客户端连接池
        $rpcClientPoolDefinition = [
            'class'  => Pool::class,
            'client' => BeanFactory::createBean('rpc.client', $rpcClientDefinition),
            'minActive' => 1,
            'maxActive' => 1,
        ];
        self::$rpcClientPool = BeanFactory::createBean('rpc.client.pool', $rpcClientPoolDefinition);

        // 创建模拟服务对象
        self::$mockRpcServer = new MockRpcServer();

        // 创建代理请求服务
        Coroutine::create(function (){
            self::$server = $server = new Server('127.0.0.1', 18307, false, true);
            $server->set([
                'open_eof_check' => true,
                'open_eof_split' => true,
                'package_eof'    => "\r\n\r\n",
            ]);
            /* @var Packet $packet */
            $packet = bean('rpcServerPacket');
            $server->handle(function (Connection $conn) use($packet){
                while (true) {
                    $data = $conn->recv();
                    if ($data === '' || $data === false) {
                        $conn->close();
                        break;
                    }
                    $params = json_decode($data, true);
                    if(isset($params['method']) && isset($params['ext'])) {
                        $method = explode('::', $params['method']);
                        $response = self::$mockRpcServer->call($method[1], $method[2], $params['params'], $params['ext'], $method[0]);
                        $conn->send($packet->encodeResponse($response->getData()));
                    }
                    Coroutine::sleep(0.01);
                }
            });

            $server->start();
        });

        // 创建客户端代理对象
        $className  = Proxy::newClassName(RpcReadOldCallInterface::class, 'user.pool_1.0');
        ReferenceRegister::register($className, 'rpc.client.pool', Protocol::DEFAULT_VERSION);
        self::$rpcReadOldCallService = new $className();

        // 初始化上下文
        $context = TestContext::new();
        Context::set($context);
    }

    /**
     * 模拟读取数据超时
     */
    public function testA(): void
    {
        $this->expectException(RpcClientException::class);
        self::$rpcReadOldCallService->getStringResult();
    }

    /**
     * 复现读取到上一个请求的数据
     */
    public function testB(): void
    {
        $result = self::$rpcReadOldCallService->getIntResult();
        $this->assertIsInt($result);
        $this->assertEquals(1, $result);
    }

    /**
     * 关闭服务和连接池
     */
    public static function tearDownAfterClass(): void
    {
        self::$rpcClientPool->close();
        self::$server->shutdown();
    }
}
