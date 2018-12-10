<?php

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
        $this->assertEquals($expected, $result);
    }

    public function testEvalArray()
    {
        $expected = [1, 2, 3];
        $result = $this->redis->eval("return {1,2,3}");
        $this->assertTrue(is_array($result));

        foreach ($result as $index => $value) {
            $this->assertEquals($expected[$index], $value);
        }
    }

    public function testScript()
    {
        $script = 'return 1';
        $sha = $this->redis->script('load', $script);
        $this->assertEquals(sha1($script), $sha);
    }

    public function testEvalSha()
    {
        $expected = 3;
        $script = "return {$expected}";
        $sha = $this->redis->script('load', $script);
        $result = $this->redis->evalSha($sha);
        $this->assertEquals($expected, $result);
    }
}