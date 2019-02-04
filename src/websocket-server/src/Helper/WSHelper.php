<?php

namespace Swoft\WebSocket\Server\Helper;

/**
 * Class WSHelper
 * @package Swoft\WebSocket\Server\Helper
 */
class WSHelper
{
    public const WS_VERSION = 13;
    public const KEY_PATTEN = '#^[+/0-9A-Za-z]{21}[AQgw]==$#';
    public const SIGN_KEY   = '258EAFA5-E914-47DA-95CA-C5AB0DC85B11';

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
