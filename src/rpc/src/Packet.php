<?php declare(strict_types=1);


namespace Swoft\Rpc;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Rpc\Contract\PacketInterface;
use Swoft\Rpc\Exception\RpcException;
use Swoft\Rpc\Packet\JsonPacket;
use Swoft\Stdlib\Helper\Arr;

/**
 * Class Packet
 *
 * @since 2.0
 *
 * @Bean()
 */
class Packet implements PacketInterface
{
    /**
     * Json packet
     */
    const JSON = 'JSON';

    /**
     * Packet type
     *
     * @var string
     */
    private $type = self::JSON;

    /**
     * Packet
     */
    private $packets = [];

    /**
     * @param Protocol $protocol
     *
     * @return string
     * @throws RpcException
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function encode(Protocol $protocol): string
    {
        $packet = $this->getPacket();
        return $packet->encode($protocol);
    }

    /**
     * @param string $string
     *
     * @return Protocol
     * @throws RpcException
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function decode(string $string): Protocol
    {
        $packet = $this->getPacket();
        return $packet->decode($string);
    }

    /**
     * @return PacketInterface
     * @throws RpcException
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function getPacket(): PacketInterface
    {
        $packets = Arr::merge($this->defaultPackets(), $this->packets);
        $packet  = $packets[$this->type] ?? null;
        if (empty($packet)) {
            throw new RpcException(
                sprintf('Packet type(%s) is not supported!', $this->type)
            );
        }

        if (!$packet instanceof PacketInterface) {
            throw new RpcException(
                sprintf('Packet type(%s) is not instanceof PacketInterface!', $this->type)
            );
        }

        return $packet;
    }

    /**
     * @return array
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function defaultPackets(): array
    {
        return [
            self::JSON => \bean(JsonPacket::class)
        ];
    }
}