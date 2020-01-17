<?php declare(strict_types=1);

namespace SwoftTest\WebSocket\Server\Testing;

use Swoft\WebSocket\Server\Message\Response;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\WebSocket\Server\WsServerBean;

/**
 * Class MockMsgResponse
 *
 * @since 2.0.8
 * @Bean(name=WsServerBean::RESPONSE, scope=Bean::PROTOTYPE)
 */
class MockMsgResponse extends Response
{

}
