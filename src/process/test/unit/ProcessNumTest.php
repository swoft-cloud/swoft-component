<?php

namespace SwoftTest\Process\Unit;

use Swoft\Process\ProcessRegister;
use SwoftTest\Process\Testing\Process\Process1;
use SwoftTest\Process\Testing\Process\Process2;
use SwoftTest\Process\Testing\Process\Process3;

/**
 * Class ProcessNumTest
 *
 * @since 2.0
 */
class ProcessNumTest extends \PHPUnit\Framework\TestCase
{
    function testNum(): void
    {
        $this->assertEquals(ProcessRegister::getWorkerNum(), 6);
    }

    function testProcessClassMap(): void
    {
        $workerNum = ProcessRegister::getWorkerNum();

        $processClassMap = [];

        for ($workerId = 0; $workerId < $workerNum; $workerId ++) {
            $class = ProcessRegister::getProcess($workerId);
            isset($processClassMap[$class]) ? $processClassMap[$class] ++ : $processClassMap[$class] = 1;
        }

        $this->assertArrayHasKey(Process1::class, $processClassMap);
        $this->assertArrayHasKey(Process2::class, $processClassMap);
        $this->assertArrayHasKey(Process3::class, $processClassMap);

        $this->assertEquals($processClassMap[Process1::class], 3);
        $this->assertEquals($processClassMap[Process2::class], 2);
        $this->assertEquals($processClassMap[Process3::class], 1);
    }
}