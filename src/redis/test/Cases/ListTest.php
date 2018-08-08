<?php

namespace SwoftTest\Redis;

/**
 * ListTest
 */
class ListTest extends AbstractTestCase
{
    public function testlPush()
    {
        go(function () {
            $key = uniqid();
            $result = $this->redis->lPush($key, 'A');
            $this->assertEquals($result, 1);
            $result = $this->redis->lPush($key, 'B');
            $this->assertEquals($result, 2);
        });
    }

    public function testlPushx()
    {
        go(function () {
            $key = uniqid();
            $result = $this->redis->lPushx($key, 'A');
            $this->assertEquals($result, 0);
            $result = $this->redis->lPush($key, 'A');
            $this->assertEquals($result, 1);
            $result = $this->redis->lPush($key, 'B');
            $this->assertEquals($result, 2);
        });
    }

    public function testrPush()
    {
        go(function () {
            $key = uniqid();
            $result = $this->redis->rPush($key, 'A');
            $this->assertEquals($result, 1);
            $result = $this->redis->rPush($key, 'B');
            $this->assertEquals($result, 2);
        });
    }

    public function testrPushx()
    {
        go(function () {
            $key = uniqid();
            $result = $this->redis->rPushx($key, 'A');
            $this->assertEquals($result, 0);
            $result = $this->redis->rPush($key, 'A');
            $this->assertEquals($result, 1);
            $result = $this->redis->rPush($key, 'B');
            $this->assertEquals($result, 2);
        });
    }

    public function testlLen()
    {
        go(function () {
            $key = uniqid();
            $result = $this->redis->lPush($key, 'A');
            $lenResult = $this->redis->lLen($key);
            $this->assertEquals($result, $lenResult);
        });
    }

    public function testlPop()
    {
        go(function () {
            $key = uniqid();
            $this->redis->lPush($key, 'A');
            $this->redis->lPush($key, 'B');
            $this->redis->lPush($key, 'C');

            $result = $this->redis->lPop($key);
            $this->assertEquals('C', $result);
        });
    }

    public function testrPop()
    {
        go(function () {
            $key = uniqid();
            $this->redis->rPush($key, 'A');
            $this->redis->rPush($key, 'B');
            $this->redis->rPush($key, 'C');

            $result = $this->redis->rPop($key);
            $this->assertEquals('C', $result);
        });
    }

    public function testlRange()
    {
        go(function () {
            $expected = [
                'A',
                'B',
                'C'
            ];
            $key = uniqid();
            foreach ($expected as $value) {
                $this->redis->rPush($key, $value);
            }

            $result = $this->redis->lRange($key, 0, -1);
            foreach ($result as $index => $value) {
                $this->assertEquals($value, $expected[ $index ]);
            }
        });
    }

    public function testlIndex()
    {
        go(function () {
            $key = uniqid();
            $this->redis->rPush($key, 'A');
            $this->redis->rPush($key, 'B');
            $this->redis->rPush($key, 'C');

            $result = $this->redis->lIndex($key, 0);
            $this->assertEquals('A', $result);

            $result = $this->redis->lIndex($key, -1);
            $this->assertEquals('C', $result);

            $result = $this->redis->lIndex($key, 10);
            $this->assertNull($result);
        });
    }

    public function testlInsert()
    {
        go(function () {
            $key = uniqid();

            $expected = [
                'A',
                'B',
                'C'
            ];

            $result = $this->redis->lInsert($key, 'after', 'A', 'X');
            $this->assertEquals($result, 0);

            foreach ($expected as $value) {
                $this->redis->lPush($key, $value);
            }

            $result = $this->redis->lInsert($key, 'before', 'C', 'X');
            array_push($expected, 'X');
            $expected = array_reverse($expected);

            $this->assertEquals($result, $this->redis->lLen($key));
            $result = $this->redis->lRange($key, 0, -1);

            foreach ($result as $index => $value) {
                $this->assertEquals($value, $expected[ $index ]);
            }

        });
    }

    public function testlRem()
    {
        go(function () {
            $key = uniqid();
            $expected = [
                'A',
                'B',
                'C',
                'A',
                'A',
                'C'
            ];

            foreach ($expected as $value) {
                $this->redis->lPush($key, $value);
            }

            $counts = array_count_values($expected);
            $result = $this->redis->lRem($key, 'A');

            $this->assertEquals($result, $counts['A']);

            $result = $this->redis->lRem($key, 'C', 1);

            $this->assertEquals($result, $counts['C'] - 1);

            $this->assertEquals($this->redis->lLen($key), 2);
        });
    }

    public function testlSet()
    {
        go(function () {
            $key = uniqid();
            $expected = [
                'A',
                'B',
                'C',
            ];

            foreach ($expected as $value) {
                $this->redis->lPush($key, $value);
            }
            $this->redis->lSet($key, 0, 'A2');

            $this->assertEquals($this->redis->lGet($key, 0), 'A2');
        });
    }

    /**
     * @bug [swoole-bug]
     */
    public function testlTrim()
    {
        return;
        go(function () {
            $key = uniqid();
            $expected = [
                'A',
                'B',
                'C',
            ];

            foreach ($expected as $value) {
                $this->redis->lPush($key, $value);
            }

            /* array('C', 'B', 'A') */
            var_dump($this->redis->lTrim($key, 0, 1));
            $expected = [
                'C',
                'B'
            ];

            /* expected:array('C', 'B'),but it will return array('C', 'B', 'A')  */
            foreach ($this->redis->lRange($key, 0, -1) as $index => $value) {
                $this->assertEquals($value, $expected[ $index ]);
            }

        });
    }

    public function testblPop()
    {
        go(function () {
            $key = uniqid();
            $expected = [
                'A',
                'B',
                'C',
            ];

            foreach ($expected as $value) {
                $this->redis->lPush($key, $value);
            }

            $this->redis->delete($key);

            $expected = 'D';

            go(function () use ($key, $expected) {
                $result = $this->redis->blPop($key, 6);
                $this->assertEquals($result[1], $expected);
            });

            go(function () use ($key, $expected) {
                \co::sleep(3.0);
                $this->redis->lPush($key, $expected);
            });
        });
    }
}