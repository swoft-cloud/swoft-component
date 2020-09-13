<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Server\Contract;

use Swoole\Server;

/**
 * Class PacketInterface
 *
 * @since 2.0
 */
interface PacketInterface
{
    /**
     * Packet event
     *
     * @param Server $server
     * @param string $data
     * @param array  $clientInfo
     */
    public function onPacket(Server $server, string $data, array $clientInfo): void;
}
