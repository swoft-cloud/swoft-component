<?php declare(strict_types=1);


namespace Swoft\Rpc\Server\Swoole;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\BeanFactory;
use Swoft\Rpc\Server\Request;
use Swoft\Rpc\Server\Response;
use Swoft\Rpc\Server\ServiceDispatcher;
use Swoft\Server\Swoole\ReceiveInterface;
use Swoole\Server;

/**
 * Class ReceiveListener
 *
 * @since 2.0
 *
 * @Bean()
 */
class ReceiveListener implements ReceiveInterface
{
    /**
     * @param Server $server
     * @param int    $fd
     * @param int    $reactorId
     * @param string $data
     *
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Rpc\Exception\RpcException
     */
    public function onReceive(Server $server, int $fd, int $reactorId, string $data): void
    {
        $request  = Request::new($server, $fd, $reactorId, $data);
        $response = Response::new($server, $fd, $reactorId);

        /* @var ServiceDispatcher $dispatcher */
        $dispatcher = BeanFactory::getSingleton('serviceDispatcher');

        $dispatcher->dispatch($request, $response);
    }
}