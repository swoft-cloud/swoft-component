<?php declare(strict_types=1);


namespace Swoft\Rpc\Server;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Concern\PrototypeTrait;
use Swoft\Rpc\Error;
use Swoft\Rpc\Packet;
use Swoft\Rpc\Server\Contract\ResponseInterface;
use Swoole\Server;

/**
 * Class Response
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::PROTOTYPE)
 */
class Response implements ResponseInterface
{
    use PrototypeTrait;

    /**
     * @var Server
     */
    private $server;

    /**
     * @var int
     */
    private $fd = 0;

    /**
     * @var int
     */
    private $reactorId = 0;

    /**
     * @var string
     */
    private $content = '';

    /**
     * @var mixed
     */
    private $data;

    /**
     * @var Error
     */
    private $error;

    /**
     * @param Server $server
     * @param int    $fd
     * @param int    $reactorId
     *
     * @return Response
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public static function new(Server $server, int $fd, int $reactorId): Response
    {
        $instance = self::__instance();

        $instance->server    = $server;
        $instance->reactorId = $reactorId;
        $instance->fd        = $fd;

        return $instance;
    }

    /**
     * @param Error $error
     *
     * @return ResponseInterface
     */
    public function withError(Error $error): ResponseInterface
    {
        $clone = clone $this;

        $clone->error = $error;
        return $clone;
    }

    /**
     * @param $data
     *
     * @return ResponseInterface
     */
    public function withData($data): ResponseInterface
    {
        $clone = clone $this;

        $clone->data = $data;
        return $clone;
    }

    /**
     * @param string $content
     *
     * @return ResponseInterface
     */
    public function withContent(string $content): ResponseInterface
    {
        $clone = clone $this;

        $clone->content = $content;
        return $clone;
    }

    /**
     * @return bool
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Rpc\Exception\RpcException
     */
    public function send(): bool
    {
        $this->prepare();
        return $this->server->send($this->fd, $this->content);
    }

    /**
     * @return Server
     */
    public function getServer(): Server
    {
        return $this->server;
    }

    /**
     * @return int
     */
    public function getFd(): int
    {
        return $this->fd;
    }

    /**
     * @return int
     */
    public function getReactorId(): int
    {
        return $this->reactorId;
    }

    /**
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Rpc\Exception\RpcException
     */
    private function prepare(): void
    {
        /* @var Packet $packet */
        $packet = \bean('rpcServerPacket');

        if ($this->error === null) {
            $this->content = $packet->encodeResponse($this->data);
            return;
        }

        $code    = $this->error->getCode();
        $message = $this->error->getMessage();
        $data    = $this->error->getData();

        $this->content = $packet->encodeResponse(null, $code, $message, $data);
    }
}