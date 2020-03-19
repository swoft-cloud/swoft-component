<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

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
