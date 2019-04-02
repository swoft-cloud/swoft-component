<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\MessageParser;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\WebSocket\Server\Contract\MessageParserInterface;

/**
 * Class TextParser
 * @since 2.0
 * @Bean()
 */
class RawTextParser implements MessageParserInterface
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
            'cmd'  => '',
            'data' => $data,
        ];
    }
}
