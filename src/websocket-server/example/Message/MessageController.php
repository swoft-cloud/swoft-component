<?php
declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\WebSocket\Server\Message;

use Swoft\DataParser\DataParserAwareTrait;
use Swoft\DataParser\JsonParser;
use Swoft\DataParser\ParserInterface;
use Swoft\Http\Message\Server\Request;
use Swoft\Http\Message\Server\Response;
use Swoft\WebSocket\Server\HandlerInterface;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

/**
 * Class MessageController
 * @package Swoft\WebSocket\Server\Message
 * @property ParserInterface $parser
 */
abstract class MessageController implements HandlerInterface
{
    use DataParserAwareTrait;

    /** @var MessageDispatcher */
    protected $dispatcher;

    /** @var string */
    protected $parserDriver = 'json';

    public function init()
    {
        $this->setParser(new JsonParser());

        $this->dispatcher = new MessageDispatcher($this->registerCommands());
    }

    /**
     * 在这里你可以验证握手的请求信息
     * - 必须返回含有两个元素的array
     *  - 第一个元素的值来决定是否进行握手
     *  - 第二个元素是response对象
     * - 可以在response设置一些自定义header,body等信息
     * @param Request $request
     * @param Response $response
     * @return array
     * [
     *  self::HANDSHAKE_OK,
     *  $response
     * ]
     */
    public function checkHandshake(Request $request, Response $response): array
    {
        return [self::HANDSHAKE_OK, $response];
    }

    /**
     * @param Server $server
     * @param Request $request
     * @param int $fd
     * @return mixed
     */
    public function onOpen(Server $server, Request $request, int $fd)
    {
        // TODO: Implement onOpen() method.
    }

    /**
     * @param Server $server
     * @param Frame $frame
     */
    public function onMessage(Server $server, Frame $frame)
    {
        $parser = $this->getParser();

        if (!$data = $parser->decode($frame->data)) {
            $server->push($frame->fd, 'your sent data is invalid');

            return;
        }

        $this->dispatcher->dispatch($data['command'], $data['body']);
    }

    /**
     * on connection closed
     * - you can do something. eg. record log
     * @param Server $server
     * @param int $fd
     * @return mixed
     */
    public function onClose(Server $server, int $fd)
    {
        // TODO: Implement onClose() method.
    }

    // protected function registerOperators(): array
    // protected function registerHandlers(): array
    protected function registerCommands(): array
    {
        return [
            'login' => 'LoginHandler',
            'message' => 'MessageHandler',
            'logout' => 'LogoutHandler',
            'createRoom' => 'CreateRoomHandler',
        ];
    }

    protected function messageDispatch()
    {
    }
}
