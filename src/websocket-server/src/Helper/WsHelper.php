<?php

namespace Swoft\WebSocket\Server\Helper;

use Swoole\Http\Request;
use Swoole\Http\Response;

/**
 * Class WsHelper
 * @since 2.0
 * @package Swoft\WebSocket\Server\Helper
 */
class WsHelper
{
    public const WS_VERSION = 13;
    public const KEY_PATTEN = '#^[+/0-9A-Za-z]{21}[AQgw]==$#';
    public const SIGN_KEY   = '258EAFA5-E914-47DA-95CA-C5AB0DC85B11';

    // websocket data opcode types
    public const OPCODE_TEXT   = 0x01;
    public const OPCODE_BINARY = 0x02;
    public const OPCODE_CLOSE  = 0x08;
    public const OPCODE_PING   = 0x09;

    /**
     * Generate WebSocket sign.(for server)
     * @param string $key
     * @return string
     */
    public static function genSign(string $key): string
    {
        return \base64_encode(\sha1(\trim($key) . self::SIGN_KEY, true));
    }

    /**
     * @param string $secWSKey 'sec-websocket-key: xxxx'
     * @return bool
     */
    public static function isInvalidSecWSKey(string $secWSKey): bool
    {
        return 0 === \preg_match(self::KEY_PATTEN, $secWSKey) ||
            16 !== \strlen(\base64_decode($secWSKey));
    }

    /**
     * @param string $secWSKey
     * @return array
     */
    public static function handshakeHeaders(string $secWSKey): array
    {
        return [
            'Upgrade'               => 'websocket',
            'Connection'            => 'Upgrade',
            'Sec-WebSocket-Accept'  => self::genSign($secWSKey),
            'Sec-WebSocket-Version' => self::WS_VERSION,
        ];
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @return bool
     */
    public static function quickHandshake(Request $request, Response $response): bool
    {
        // $this->log("received handshake request from fd #{$request->fd}, co ID #" . Coroutine::tid());

        // websocket握手连接算法验证
        $secWSKey = $request->header['sec-websocket-key'];

        if (self::isInvalidSecWSKey($secWSKey)) {
            $response->end();
            return false;
        }

        $headers = self::handshakeHeaders($secWSKey);

        // WebSocket connection to 'ws://127.0.0.1:9502/'
        // failed: Error during WebSocket handshake:
        // Response must not include 'Sec-WebSocket-Protocol' header if not present in request: websocket
        if (isset($request->header['sec-websocket-protocol'])) {
            $headers['Sec-WebSocket-Protocol'] = $request->header['sec-websocket-protocol'];
        }

        foreach ($headers as $key => $val) {
            $response->header($key, $val);
        }

        $response->status(101);
        $response->end();
        return true;
    }

    /**
     * @param string $path
     * @return string
     */
    public static function formatPath(string $path): string
    {
        $path = \rtrim($path, '/ ');

        return $path ?: '/';
    }

    /**
     * @param int    $fd
     * @param string $prefix
     * @return string (length is 32)
     * @throws \Exception
     */
    public static function generateId(int $fd, string $prefix = ''): string
    {
        // 参照 mongoDb ID: Time + Machine + PID + INC
        return $prefix .
            \date('YmdHis') .
            \hash('crc32', \php_uname()) .
            \str_pad(\dechex(\getmypid()), 4, 0, STR_PAD_LEFT) .
            \str_pad(\dechex($fd), 6, 0, STR_PAD_LEFT);
        // \dechex(\random_int(2000000, 16000000));
    }
}
