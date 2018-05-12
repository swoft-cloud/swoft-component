<?php

namespace SwoftTest\Task;

use PHPUnit\Framework\TestCase;
use Swoft\Task\Helper\CronExpression;

class AbstractTestCase extends TestCase
{
    /**
     * @param string $express
     * @param string $time
     */
    protected function assertExpressionTrue(string $express, string $time)
    {
        $result = CronExpression::validateExpression($express, strtotime($time));
        $this->assertTrue($result);
    }

    /**
     * @param string $express
     * @param string $time
     */
    protected function assertExpressionFalse(string $express, string $time)
    {
        $result = CronExpression::validateExpression($express, strtotime($time));
        $this->assertFalse($result);
    }
}