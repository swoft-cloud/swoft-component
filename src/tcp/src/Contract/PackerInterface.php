<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Tcp\Contract;

use Swoft\Tcp\Package;
use Swoft\Tcp\Response;

/**
 * Interface PackerInterface - Data packer interface
 *
 * @since 2.0.3
 */
interface PackerInterface
{
    /**
     * The data packer type name.
     *
     * @return string
     */
    public static function getType(): string;

    /**
     * Encode [Package] to string for request server
     *
     * @param Package $package
     *
     * @return string
     */
    public function encode(Package $package): string;

    /**
     * Decode client request body data to [Package] object
     *
     * @param string $data package data
     *
     * @return Package
     */
    public function decode(string $data): Package;

    /**
     * Encode [Response] to string for response client
     *
     * @param Response $response
     *
     * @return string
     */
    public function encodeResponse(Response $response): string;

    /**
     * Decode the server response data as an [Response]
     *
     * @param string $data package data
     *
     * @return Response
     */
    public function decodeResponse(string $data): Response;
}
