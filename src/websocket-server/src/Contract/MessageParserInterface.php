<?php

namespace Swoft\WebSocket\Server\Contract;

/**
 * Interface MessageParserInterface
 * @since 2.0
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
     * @param string $data
     * @return array
     */
    public function decode(string $data): array;
}
