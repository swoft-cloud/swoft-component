<?php declare(strict_types=1);

namespace SwoftTest\WebSocket\Server\Testing;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\WebSocket\Server\Connection;
use Swoft\WebSocket\Server\WsServerBean;

/**
 * Class MockConnection
 * @Bean(name=WsServerBean::CONNECTION, scope=Bean::PROTOTYPE)
 */
class MockConnection extends Connection
{
}
