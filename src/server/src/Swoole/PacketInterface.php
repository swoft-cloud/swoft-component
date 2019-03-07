<?php declare(strict_types=1);

namespace Swoft\Server\Swoole;

use Swoole\Server;

/**
 * Interface PacketInterface
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