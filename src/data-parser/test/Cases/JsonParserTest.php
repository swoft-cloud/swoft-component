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

namespace SwoftTest\DataParser\Cases;

use PHPUnit\Framework\TestCase;
use Swoft\DataParser\JsonParser;

/**
 * Class JsonParserTest
 * @covers JsonParser
 */
class JsonParserTest extends TestCase
{
    public function testDecode()
    {
        $str = '{"name": "value"}';

        $parser = new JsonParser();
        $ret = $parser->decode($str);

        $this->assertInternalType('array', $ret);
        $this->assertArrayHasKey('name', $ret);
    }

    public function testEncode()
    {
        $data = [
            'name' => 'value',
        ];

        $parser = new JsonParser();
        $ret = $parser->encode($data);

        $this->assertInternalType('string', $ret);
        $this->assertJson($ret);
    }
}
