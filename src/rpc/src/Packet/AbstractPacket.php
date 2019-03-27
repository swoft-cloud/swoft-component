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
     *
     * @Config("rpc.open_eof_check")
     */
    protected $openEofCheck = false;

    /**
     * @var string
     *
     * @Config("rpc.package_eof")
     */
    protected $packageEof = '';

    /**
     * @var bool
     *
     * @Config("rpc.open_eof_split")
     */
    protected $openEofSplit = false;
}