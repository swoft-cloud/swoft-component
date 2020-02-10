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
use Swoft\Stdlib\Helper\JsonHelper;
use Swoft\Tcp\Contract\PackerInterface;
use Swoft\Tcp\Package;
use Swoft\Tcp\Response;

/**
 * Class JsonPacker
 *
 * @since 2.0.3
 * @Bean()
 */
class JsonPacker implements PackerInterface
{
    public const TYPE = 'json';

    /**
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
        return JsonHelper::encode($package->toArray());
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
        $map = JsonHelper::decode($data, true);

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

        return JsonHelper::encode($response->toArray());
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
        $map  = JsonHelper::decode($data, true);

        $resp->setContent($data);
        $resp->initFromArray($map);

        return $resp;
    }
}
