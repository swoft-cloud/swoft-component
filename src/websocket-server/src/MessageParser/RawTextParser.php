<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\MessageParser;

use ReflectionException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Exception\ContainerException;
use Swoft\WebSocket\Server\Contract\MessageParserInterface;
use Swoft\WebSocket\Server\Message\Message;

/**
 * Class TextParser
 *
 * @since 2.0
 * @Bean()
 */
class RawTextParser implements MessageParserInterface
{
    /**
     * Encode Message data to string.
     *
     * @param Message $message
     *
     * @return string
     */
    public function encode(Message $message): string
    {
        return $message->toString();
    }

    /**
     * Decode data to Message.
     *
     * @param string $data
     *
     * @return Message
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function decode(string $data): Message
    {
        return Message::new('', $data);
    }
}
