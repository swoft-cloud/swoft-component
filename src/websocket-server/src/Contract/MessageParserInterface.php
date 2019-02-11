<?php

namespace Swoft\WebSocket\Server\Contract;

/**
 * Interface MessageParserInterface
 * @since 2.0
 * @package Swoft\WebSocket\Server\Contract
 */
interface MessageParserInterface
{
    public function encode();
    public function decode();
}
