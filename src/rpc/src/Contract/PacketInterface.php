<?php declare(strict_types=1);


namespace Swoft\Rpc\Contract;

use Swoft\Rpc\Protocol;

/**
 * Class PacketInterface
 *
 * @since 2.0
 */
interface PacketInterface
{
    /**
     * @param Protocol $protocol
     *
     * @return string
     */
    public function encode(Protocol $protocol): string;

    /**
     * @param string $string
     *
     * @return Protocol
     */
    public function decode(string $string): Protocol;
}