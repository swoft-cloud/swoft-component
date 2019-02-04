<?php

namespace Swoft\WebSocket\Server\Message;

use Swoft\DataParser\DataParserAwareTrait;
use Swoft\DataParser\DataParserInterface;
use Swoft\DataParser\JsonParser;
use Swoft\Http\Message\Server\Request;
use Swoft\Http\Message\Server\Response;
use Swoft\WebSocket\Server\ModuleInterface;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

/**
 * Class MessageController
 * @package Swoft\WebSocket\Server\Message
 * @property DataParserInterface $parser
 */
abstract class MessageController implements ModuleInterface
{
    use DataParserAwareTrait;

    /** @var array */
    protected $options = [];

    /** @var MessageDispatcher */
    protected $dispatcher;

    /** @var string */
    protected $defaultParser = JsonParser::class;

    /** @var string */
    protected $defaultCommand = 'default';

    public function init()
    {
        if (!$this->parser) {
            $this->setParser(new JsonParser());
        }

        $this->options    = $this->configure();
        $this->dispatcher = new MessageDispatcher($this->registerCommands());
    }

    /**
     * @todo
     * @return array
     */
    protected function configure(): array
    {
        return [
            'pingInterval' => 10000, // ms
            'pingTimeout'  => 5000, // ms
        ];
    }

    // protected function registerOperators(): array
    // protected function registerHandlers(): array
    protected function registerCommands(): array
    {
        return [
            // handler is a method name in the controller, or is a class implement CommandInterface
            // command name => handler
            'default' => 'defaultCommand',
            // 'login' => 'LoginHandler',
            // 'message' => 'MessageHandler',
            // 'logout' => 'LogoutHandler',
            // 'createRoom' => 'CreateRoomHandler',
            // 'some command' => 'Some::class',
        ];
    }

    /**
     * @param mixed $body
     * @param Frame $frame
     */
    public function defaultCommand($body, Frame $frame)
    {
        //\ws()->send("hello, we have received your message: $body", $frame->fd);
        \ws()->push($frame->fd, "hello, we have received your message: $body");
    }

    /**
     * 在这里你可以验证握手的请求信息
     * - 必须返回含有两个元素的array
     *  - 第一个元素的值来决定是否进行握手
     *  - 第二个元素是response对象
     * - 可以在response设置一些自定义header,body等信息
     * @param Request  $request
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
     * @param Frame  $frame
     * data structure:
     * [
     *  'cmd' => 'name', // command name
     *  'body' => ...    // body data
     * ]
     */
    final public function onMessage(Server $server, Frame $frame)
    {
        $cmd    = $this->defaultCommand;
        $parser = $this->getParser();

        if (!$data = $parser->decode($frame->data)) {
            $skip = $this->onFormatError($frame);

            if ($skip === false) {
                return;
            }

            $body = $frame->data;
        } else {
            $body = $data['body'] ?: [];

            if (isset($data['cmd'])) {
                $cmd = $data['cmd'];
            } elseif (!$body) {
                $body = $data;
            }
        }

        if (false === $this->beforeDispatch($cmd, $body, $frame)) {
            return;
        }

        $this->dispatcher->dispatch($this, $cmd, $body, $frame);
        \var_dump(__METHOD__);
    }

    /**
     * @param string $cmd
     * @param mixed  $body
     * @param Frame  $frame
     * @return bool
     */
    protected function beforeDispatch(string $cmd, $body, Frame $frame): bool
    {
        // \ws()->push($frame->fd, 'your sent data is invalid');
        return true;
    }

    /**
     * @param Frame $frame
     */
    protected function onFormatError(Frame $frame)
    {
        \ws()->push($frame->fd, 'your sent data format is invalid');
    }

    /**
     * on connection closed
     * - you can do something. eg. record log
     * @param Server $server
     * @param int    $fd
     * @return mixed
     */
    public function onClose(Server $server, int $fd)
    {
        // TODO: Implement onClose() method.
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
