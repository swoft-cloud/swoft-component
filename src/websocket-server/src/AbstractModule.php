<?php

namespace Swoft\WebSocket\Server;

use Swoft\WebSocket\Server\Contract\MessageParserInterface;
use Swoft\WebSocket\Server\Contract\RequestHandlerInterface;
use Swoft\WebSocket\Server\Router\MessageDispatcher;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

/**
 * Class AbstractModule
 * @since 2.0
 * @package Swoft\WebSocket\Server
 */
abstract class AbstractModule implements RequestHandlerInterface
{
    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var MessageParserInterface
     */
    protected $parser;

    /**
     * @var MessageDispatcher
     */
    protected $dispatcher;

    /**
     * @var string
     */
    protected $defaultCommand = 'default';

    /**
     * command handlers map
     * @var array
     * handler is a method name in ws controller, or is a class implement MessageHandlerInterface
     * [
     *   'command name' => 'callback handler'
     * ]
     */
    private $handlers = [];

    public function init(): void
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
     * @param Frame $frame
     */
    public function defaultCommand(Frame $frame): void
    {
        //\ws()->send("hello, we have received your message: $body", $frame->fd);
        \server()->push($frame->fd, "hello, we have received your message: {$frame->data}");
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
    final public function onMessage(Server $server, Frame $frame): void
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
        \server()->push($frame->fd, 'your sent data format is invalid');
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

    /**
     * @return array
     */
    public function getHandlers(): array
    {
        return $this->handlers;
    }
}
