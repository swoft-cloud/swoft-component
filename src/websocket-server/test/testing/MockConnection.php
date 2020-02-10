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
