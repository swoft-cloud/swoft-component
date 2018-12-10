<?php

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
 * Interface ParserInterface
 * @package Swoft\DataParser
 * @author inhere <in.798@qq.com>
 */
interface ParserInterface
{
    /**
     * @param mixed $data
     * @return string
     */
    public function encode($data): string;

    /**
     * @param string $data
     * @return mixed
     */
    public function decode(string $data);
}
