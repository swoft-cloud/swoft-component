<?php
declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\DataParser;

/**
 * Class MsgPackParser
 * @package Swoft\DataParser
 * @author inhere <in.798@qq.com>
 */
class MsgPackParser implements ParserInterface
{
    /**
     * class constructor.
     * @throws \RuntimeException
     */
    public function __construct()
    {
        if (!\function_exists('msgpack_pack')) {
            throw new \RuntimeException("The php extension 'msgpack' is required!");
        }
    }

    /**
     * @param mixed $data
     * @return string
     */
    public function encode($data): string
    {
        return \msgpack_pack($data);
    }

    /**
     * @param string $data
     * @return mixed
     */
    public function decode(string $data)
    {
        return \msgpack_unpack($data);
    }
}
