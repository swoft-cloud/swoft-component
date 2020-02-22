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
use function explode;
use function trim;

/**
 * Class SimpleTokenPacker
 *
 * @since 2.0.3
 * @Bean()
 */
class SimpleTokenPacker implements PackerInterface
{
    public const TYPE = 'token-text';

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
        if ($cmd = $package->getCmd()) {
            return $cmd . ' ' . $package->getDataString();
        }

        return $package->getDataString();
    }

    /**
     * Decode client request body data to [Package] object
     *
     * Data format like:
     *      login message text
     *  =>
     *      cmd: 'login'
     *      data: 'message text'
     *
     * @param string $data Request package data, use first space to split cmd and data.
     *
     * @return Package
     */
    public function decode(string $data): Package
    {
        $data = trim($data);

        if (strpos($data, ' ')) {
            [$cmd, $body] = explode(' ', $data, 2);
            $cmd = trim($cmd);
        } else {
            $body = '';
            $cmd  = $data;
        }

        return Package::new($cmd, $body);
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

        if ($content = $response->getDataString()) {
            return $content;
        }

        // Has error, output error message
        if ($response->isFail()) {
            return $response->getMsg();
        }

        return '';
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
        $resp->setData($data);
        $resp->setContent($data);

        return $resp;
    }
}
