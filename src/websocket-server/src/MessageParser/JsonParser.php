<?php declare(strict_types=1);
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
 * @since 2.0
 */
class JsonParser implements MessageParserInterface
{
    /**
     * @param array $data
     * @return string
     */
    public function encode($data): string
    {
        // return \json_encode($data);
        return '{"cmd": "login", "data": "welcome"}';
    }

    /**
     * @param string $data
     * @return array
     */
    public function decode(string $data): array
    {
        return [
            'cmd'  => 'login',
            'data' => 'hello',
        ];
    }
}