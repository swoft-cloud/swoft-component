<?php

namespace SwoftTest\Redis;

/**
 * ServerTest
 */
class ServerTest extends AbstractTestCase
{
    public function testEvalNumber()
    {
        go(function () {
            $expected = 3;
            $result = $this->redis->eval("return {$expected}");
            $this->assertEquals($expected, $result);
        });
    }

    public function testEvalArray()
    {
        go(function () {
            $expected = [1, 2, 3];
            $result = $this->redis->eval("return {1,2,3}");
            $this->assertTrue(is_array($result));

            foreach ($result as $index => $value) {
                $this->assertEquals($expected[$index], $value);
            }
        });
    }

    public function testScript()
    {
        go(function () {
            $script = 'return 1';
            $sha = $this->redis->script('load', $script);
            $this->assertEquals(sha1($script), $sha);
        });
    }

    public function testEvalSha()
    {
        go(function () {
            $expected = 3;
            $script = "return {$expected}";
            $sha = $this->redis->script('load', $script);
            $result = $this->redis->evalSha($sha);
            $this->assertEquals($expected, $result);
        });
    }
}