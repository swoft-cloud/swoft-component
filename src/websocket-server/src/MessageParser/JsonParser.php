<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2019-02-12
 * Time: 13:06
 */

namespace Swoft\WebSocket\Server\MessageParser;

use Swoft\WebSocket\Server\Contract\MessageParserInterface;

/**
 * Class JsonParser
 * @package Swoft\WebSocket\Server\MessageParser
 */
class JsonParser implements MessageParserInterface
{
    public function encode(): string
    {
        return '{"cmd": "login", "data": "welcome"}';
    }

    public function decode(): array
    {
        return [
            'cmd' => 'login',
            'data' => 'hello',
        ];
    }
}