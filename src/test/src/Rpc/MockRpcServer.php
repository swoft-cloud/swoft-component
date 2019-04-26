<?php declare(strict_types=1);


namespace Swoft\Test\Rpc;


use Swoft\Bean\BeanFactory;
use Swoft\Rpc\Packet\JsonPacket;
use Swoft\Rpc\Protocol;
use Swoft\Rpc\Server\ServiceDispatcher;

/**
 * Class MockRpcServer
 *
 * @since 2.0
 */
class MockRpcServer
{
    /**
     * @param string $interface
     * @param string $method
     * @param array  $params
     * @param array  $ext
     * @param string $v
     *
     * @return MockResponse
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Rpc\Exception\RpcException
     */
    public function call(
        string $interface,
        string $method,
        array $params = [],
        array $ext = [],
        string $v = Protocol::DEFAULT_VERSION
    ) {
        /* @var JsonPacket $packet */
        $packet = BeanFactory::getBean(JsonPacket::class);

        $protocol = Protocol::new($v, $interface, $method, $params, $ext);
        $data     = $packet->encode($protocol);

        $request  = MockRequest::new(null, 1, 1, $data);
        $response = MockResponse::new(null, 1, 1);

        return $this->onReceive($request, $response);
    }

    /**
     * @param MockRequest  $request
     * @param MockResponse $response
     *
     * @return MockResponse
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    private function onReceive(MockRequest $request, MockResponse $response)
    {
        /* @var ServiceDispatcher $dispatcher */
        $dispatcher = BeanFactory::getSingleton('serviceDispatcher');

        $dispatcher->dispatch($request, $response);

        return $response;
    }
}