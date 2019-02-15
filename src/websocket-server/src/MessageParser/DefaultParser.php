<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2019-02-12
 * Time: 20:13
 */

namespace Swoft\WebSocket\Server\MessageParser;

use Swoft\WebSocket\Server\Contract\MessageParserInterface;

/**
 * Class DefaultParser
 * @package Swoft\WebSocket\Server\MessageParser
 */
class DefaultParser implements MessageParserInterface
{
    /**
     * Encode data to string.
     * @param mixed $data
     * @return string
     */
    public function encode($data): string
    {
        return (string)$data;
    }

    /**
     * Decode data to array.
     * @param string $data
     * @return array
     */
    public function decode(string $data): array
    {
        return [
            // use default message command
            'cmd' => '',
            'data' => $data,
        ];
    }
}