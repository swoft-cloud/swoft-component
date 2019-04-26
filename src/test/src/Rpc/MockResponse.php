<?php declare(strict_types=1);


namespace Swoft\Test\Rpc;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Rpc\Packet;
use Swoft\Rpc\Server\Response;
use Swoft\Test\Concern\RpcResponseAssertTrait;

/**
 * Class MockResponse
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::PROTOTYPE)
 */
class MockResponse extends Response
{
    use RpcResponseAssertTrait;

    /**
     * @var \Swoft\Rpc\Response
     */
    protected $returnResponse;

    /**
     * @return bool
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Rpc\Exception\RpcException
     */
    public function send(): bool
    {
        /* @var Packet $packet */
        $packet = \bean('rpcServerPacket');

        $this->prepare();
        $this->returnResponse = $packet->decodeResponse($this->content);
        return true;
    }
}