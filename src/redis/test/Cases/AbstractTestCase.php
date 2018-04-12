<?php

namespace SwoftTest\Redis;

use PHPUnit\Framework\TestCase;

abstract class AbstractTestCase extends TestCase
{
    protected function tearDown()
    {
        parent::tearDown();
        swoole_timer_after(1 * 1000, function () {
            swoole_event_exit();
        });
    }

    protected function setCoName($name): String
    {
        $name = "{$name}-co";

        return $name;
    }
}