<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\WebSocket\Server\MessageParser;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Stdlib\Helper\JsonHelper;
use Swoft\WebSocket\Server\Contract\MessageParserInterface;
use Swoft\WebSocket\Server\Message\Message;

/**
 * Class JsonParser
 *
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
     * @return Message
     */
    public function decode(string $data): Message
    {
        $map = JsonHelper::decode($data, true);

        return Message::newFromArray($map);
    }
}
