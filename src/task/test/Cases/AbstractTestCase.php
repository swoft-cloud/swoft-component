<?php

namespace SwoftTest\Task;

use PHPUnit\Framework\TestCase;
use Swoft\Task\Helper\Express;

class AbstractTestCase extends TestCase
{
    /**
     * @param string $express
     * @param string $time
     */
    protected function assertExpressTrue(string $express, string $time)
    {
        $result = Express::validateExpress($express, strtotime($time));
        $this->assertTrue($result);
    }

    /**
     * @param string $express
     * @param string $time
     */
    protected function assertExpressFalse(string $express, string $time)
    {
        $result = Express::validateExpress($express, strtotime($time));
        $this->assertFalse($result);
    }
}