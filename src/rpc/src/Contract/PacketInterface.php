<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Rpc\Contract;

use Swoft\Rpc\Error;
use Swoft\Rpc\Protocol;
use Swoft\Rpc\Response;

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

    /**
     * @param mixed  $result
     * @param int    $code
     * @param string $message
     * @param Error  $data
     *
     * @return string
     */
    public function encodeResponse($result, int $code = null, string $message = '', $data = null): string;

    /**
     * @param string $string
     *
     * @return Response
     */
    public function decodeResponse(string $string): Response;
}
