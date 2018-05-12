<?php

namespace SwoftTest\Task;

/**
 * SwoftExpressTest
 */
class SwoftExpressTest extends AbstractTestCase
{
    public function testSecond()
    {
        $expres = '*/2 * * * * *';
        $this->assertExpressTrue($expres, '2018-05-06 19:00:00');
        $this->assertExpressFalse($expres, '2018-05-06 19:01:01');
        $this->assertExpressTrue($expres, '2018-05-06 19:02:02');
        $this->assertExpressFalse($expres, '2018-05-06 19:05:05');
        $this->assertExpressTrue($expres, '2018-05-06 19:06:06');
        $this->assertExpressFalse($expres, '2018-05-06 19:06:07');

        $expres = '2-10/3 * * * * *';
        $this->assertExpressTrue($expres, '2018-05-06 19:02:02');
        $this->assertExpressTrue($expres, '2018-05-06 19:05:05');
        $this->assertExpressTrue($expres, '2018-05-06 19:08:08');
        $this->assertExpressFalse($expres, '2018-05-06 19:10:10');

        $expres = '12-15,3-6/2 * * * * *';
        $this->assertExpressFalse($expres, '2018-05-06 19:11:11');
        $this->assertExpressTrue($expres, '2018-05-06 19:12:12');
        $this->assertExpressTrue($expres, '2018-05-06 19:13:13');
        $this->assertExpressTrue($expres, '2018-05-06 19:15:15');
        $this->assertExpressFalse($expres, '2018-05-06 19:16:16');
        $this->assertExpressTrue($expres, '2018-05-06 19:03:03');
        $this->assertExpressFalse($expres, '2018-05-06 19:04:04');
        $this->assertExpressTrue($expres, '2018-05-06 19:05:05');
        $this->assertExpressFalse($expres, '2018-05-06 19:06:06');
    }
}