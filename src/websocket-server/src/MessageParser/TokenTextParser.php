<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\MessageParser;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\WebSocket\Server\Contract\MessageParserInterface;
use Swoft\WebSocket\Server\Message\Message;

/**
 * Class TokenTextParser
 * @since 2.0
 * @Bean()
 */
class TokenTextParser implements MessageParserInterface
{
    /**
     * Encode data to string.
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
     * Decode data to array
     *
     * @param string $data
     *  Format like:
     *  'login:message body data'
     *  =>
     *  cmd: 'login'
     *  body: 'message body data'
     *
     * @return Message
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function decode(string $data): Message
    {
        // use default message command
        $cmd = '';
        if (\strpos($data, ':')) {
            [$cmd, $body] = \explode(':', $data, 2);
            $cmd = \trim($cmd);
        } else {
            $body = $data;
        }

        return Message::new($cmd, $body);
    }
}
