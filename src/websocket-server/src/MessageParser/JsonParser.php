<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\MessageParser;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Stdlib\Helper\JsonHelper;
use Swoft\WebSocket\Server\Contract\MessageParserInterface;

/**
 * Class JsonParser
 * @since 2.0
 * @Bean()
 */
class JsonParser implements MessageParserInterface
{
    /**
     * @param array $data
     * @return string
     */
    public function encode($data): string
    {
        return JsonHelper::encode($data);
    }

    /**
     * Decode data to array.
     * @param string $data Message data. It's {@see \Swoole\WebSocket\Frame::$data)
     * @return array
     * [
     *  'cmd'  => 'home.index', // message command. it's must exists.
     *  'data' => message data,
     *  ...
     * ]
     */
    public function decode(string $data): array
    {
        $map = JsonHelper::decode($data, true);

        return isset($map['cmd']) ? $map : [
            'cmd'  => '',
            'data' => $map,
        ];
    }
}
