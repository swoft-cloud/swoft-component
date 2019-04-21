<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\Contract;

/**
 * Interface MessageParserInterface
 * @since   2.0
 * @package Swoft\WebSocket\Server\Contract
 */
interface MessageParserInterface
{
    /**
     * Encode data to string.
     * @param array|string|mixed $data
     * @return string
     */
    public function encode($data): string;

    /**
     * Decode data to array.
     * @param string $data Message data. It's {@see \Swoole\WebSocket\Frame::$data)
     * @return array
     *                     [
     *                     'cmd'  => 'home.index', // message command. it's must exists.
     *                     'data' => message data,
     *                     ...
     *                     ]
     */
    public function decode(string $data): array;
}
