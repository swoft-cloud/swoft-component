<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\MessageParser;

use ReflectionException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Stdlib\Helper\JsonHelper;
use Swoft\WebSocket\Server\Contract\MessageParserInterface;
use Swoft\WebSocket\Server\Message\Message;

/**
 * Class JsonParser
 * @since 2.0
 * @Bean()
 */
class JsonParser implements MessageParserInterface
{
    /**
     * @param Message $message
     *
     * @return string
     */
    public function encode(Message $message): string
    {
        return JsonHelper::encode($message->toArray());
    }

    /**
     * Decode data to array.
     *
     * @param string $data Message data. It's {@see \Swoole\WebSocket\Frame::$data)
     *
     * @return Message [
     *                     [
     *                      'cmd'  => 'home.index', // message command. it's must exists.
     *                      'data' => message data,
     *                      ...
     *                     ]
     *                  ]
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function decode(string $data): Message
    {
        $cmd = '';
        $map = JsonHelper::decode($data, true);

        if (isset($map['cmd'])) {
            $cmd = (string)$map['cmd'];
            unset($map['cmd']);
        }

        return Message::new($cmd, $map['data'] ?? $map);
    }
}
