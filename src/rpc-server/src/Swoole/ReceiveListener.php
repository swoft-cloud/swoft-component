<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Rpc\Server\Swoole;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\BeanFactory;
use Swoft\Rpc\Exception\RpcException;
use Swoft\Rpc\Server\Request;
use Swoft\Rpc\Server\Response;
use Swoft\Rpc\Server\ServiceDispatcher;
use Swoft\Server\Contract\ReceiveInterface;
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
     * @throws RpcException
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
