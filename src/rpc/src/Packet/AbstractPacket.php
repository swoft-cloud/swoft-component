<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Rpc\Packet;

use Swoft\Rpc\Contract\PacketInterface;
use Swoft\Rpc\Packet;

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
     * @var Packet
     */
    protected $packet;

    /**
     * @param Packet $packet
     */
    public function initialize(Packet $packet): void
    {
        $this->packet = $packet;
    }

    /**
     * @param string $string
     *
     * @return string
     */
    protected function addPackageEof(string $string): string
    {
        // Fix mock server null
        if (empty($this->packet)) {
            return $string;
        }

        if ($this->packet->isOpenEofCheck() || $this->packet->isOpenEofSplit()) {
            $string .= $this->packet->getPackageEof();
        }

        return $string;
    }
}
