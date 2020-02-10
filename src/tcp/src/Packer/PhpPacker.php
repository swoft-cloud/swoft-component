<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Tcp\Packer;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Tcp\Contract\PackerInterface;
use Swoft\Tcp\Package;
use Swoft\Tcp\Response;
use function serialize;
use function unserialize;

/**
 * Class PhpPacker
 *
 * @since 2.0.4
 * @Bean()
 */
class PhpPacker implements PackerInterface
{
    public const TYPE = 'php';

    /**
     * The data packer type name.
     *
     * @return string
     */
    public static function getType(): string
    {
        return self::TYPE;
    }

    /**
     * Encode [Package] to string for request server
     *
     * @param Package $package
     *
     * @return string
     */
    public function encode(Package $package): string
    {
        return serialize($package->toArray());
    }

    /**
     * Decode client request body data to [Package] object
     *
     * @param string $data package data
     *
     * @return Package
     */
    public function decode(string $data): Package
    {
        $map = (array)unserialize($data, ['allowed_classes' => false]);

        return Package::newFromArray($map);
    }

    /**
     * Encode [Response] to string for response client
     *
     * @param Response $response
     *
     * @return string
     */
    public function encodeResponse(Response $response): string
    {
        // If Response.content is not empty
        if ($content = $response->getContent()) {
            return $content;
        }

        return serialize($response->toArray());
    }

    /**
     * Decode the server response data as an [Response]
     *
     * @param string $data package data
     *
     * @return Response
     */
    public function decodeResponse(string $data): Response
    {
        $resp = new Response();
        $map  = (array)unserialize($data, ['allowed_classes' => false]);

        $resp->setContent($data);
        $resp->initFromArray($map);

        return $resp;
    }
}
