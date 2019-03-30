<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\MessageParser;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\WebSocket\Server\Contract\MessageParserInterface;

/**
 * Class TokenTextParser
 * @since 2.0
 * @Bean()
 */
class TokenTextParser implements MessageParserInterface
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
     * Format like:
     *  'login:message body data'
     * =>
     *  cmd: 'login'
     *  body: 'message body data'
     * @return array
     */
    public function decode(string $data): array
    {
        // use default message command
        $cmd = '';
        if (\strpos($data, ':')) {
            [$cmd, $body] = \explode(':', $data, 2);
            $cmd = \trim($cmd);
        } else {
            $body = $data;
        }

        return [
            'cmd'  => $cmd,
            'data' => $body,
        ];
    }
}
