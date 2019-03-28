<?php declare(strict_types=1);


namespace Swoft\Rpc\Packet;


use Swoft\Config\Annotation\Mapping\Config;
use Swoft\Rpc\Contract\PacketInterface;

/**
 * Class AbstractPacket
 *
 * @since 2.0
 */
abstract class AbstractPacket implements PacketInterface
{
    /**
     * delimiter
     */
    const DELIMITER = '::';

    /**
     * @var bool
     */
    protected $openEofCheck = false;

    /**
     * @var string
     */
    protected $packageEof = '';

    /**
     * @var bool
     */
    protected $openEofSplit = false;
}