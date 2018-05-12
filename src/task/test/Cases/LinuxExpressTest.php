<?php

namespace SwoftTest\Task;

/**
 * LinuxExpressTest
 */
class LinuxExpressTest extends AbstractTestCase
{
    public function testMinute()
    {
        $expres = '*/2 * * * *';
        $this->assertExpressTrue($expres, '2018-05-06 19:00:00');
        $this->assertExpressFalse($expres, '2018-05-06 19:01:00');
        $this->assertExpressTrue($expres, '2018-05-06 19:02:00');
        $this->assertExpressFalse($expres, '2018-05-06 19:05:00');
        $this->assertExpressTrue($expres, '2018-05-06 19:06:00');
        $this->assertExpressFalse($expres, '2018-05-06 19:06:01');

        $expres = '2-10/3 * * * *';
        $this->assertExpressTrue($expres, '2018-05-06 19:02:00');
        $this->assertExpressTrue($expres, '2018-05-06 19:05:00');
        $this->assertExpressTrue($expres, '2018-05-06 19:08:00');
        $this->assertExpressFalse($expres, '2018-05-06 19:10:00');

        $expres = '12-15,3-6/2 * * * *';
        $this->assertExpressFalse($expres, '2018-05-06 19:11:00');
        $this->assertExpressTrue($expres, '2018-05-06 19:12:00');
        $this->assertExpressTrue($expres, '2018-05-06 19:13:00');
        $this->assertExpressTrue($expres, '2018-05-06 19:15:00');
        $this->assertExpressFalse($expres, '2018-05-06 19:16:00');
        $this->assertExpressTrue($expres, '2018-05-06 19:03:00');
        $this->assertExpressFalse($expres, '2018-05-06 19:04:00');
        $this->assertExpressTrue($expres, '2018-05-06 19:05:00');
        $this->assertExpressFalse($expres, '2018-05-06 19:06:00');
    }
}