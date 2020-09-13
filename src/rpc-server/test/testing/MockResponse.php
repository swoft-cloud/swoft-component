<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Rpc\Server\Testing;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Rpc\Exception\RpcException;
use Swoft\Rpc\Packet;
use Swoft\Rpc\Server\Response;
use SwoftTest\Rpc\Server\Testing\Concern\RpcResponseAssertTrait;
use function bean;

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
     * @throws RpcException
     */
    public function send(): bool
    {
        /* @var Packet $packet */
        $packet = bean('rpcServerPacket');

        $this->prepare();
        $this->returnResponse = $packet->decodeResponse($this->content);
        return true;
    }
}
