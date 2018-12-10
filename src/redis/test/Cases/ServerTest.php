<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace SwoftTest\Redis\Cases;

/**
 * ServerTest
 */
class ServerTest extends AbstractTestCase
{
    public function testEvalNumber()
    {
        $expected = 3;
        $result = $this->redis->eval("return {$expected}");
        $this->assertSame($expected, $result);
    }

    public function testEvalArray()
    {
        $expected = [1, 2, 3];
        $result = $this->redis->eval('return {1,2,3}');
        $this->assertTrue(is_array($result));

        foreach ($result as $index => $value) {
            $this->assertSame($expected[$index], $value);
        }
    }

    public function testScript()
    {
        $script = 'return 1';
        $sha = $this->redis->script('load', $script);
        $this->assertSame(sha1($script), $sha);
    }

    public function testEvalSha()
    {
        $expected = 3;
        $script = "return {$expected}";
        $sha = $this->redis->script('load', $script);
        $result = $this->redis->evalSha($sha);
        $this->assertSame($expected, $result);
    }
}
