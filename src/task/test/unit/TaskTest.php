<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Task\Unit;

use Swoft\Context\Context;
use Swoft\Task\Exception\TaskException;

/**
 * Class TaskTest
 *
 * @since 2.0
 */
class TaskTest extends TestCase
{
    /**
     * @throws TaskException
     * @expectedException \Swoft\Task\Exception\TaskException
     */
    public function testCo(): void
    {
        $this->mockTaskServer->co('demoTestTask', 'method', []);
    }

    /**
     * @throws TaskException
     */
    public function testCo2(): void
    {
        $data   = [
            'name',
            18306
        ];
        $result = $this->mockTaskServer->co('demoTestTask', 'method', ['name', 18306]);
        $this->assertEquals($result, $data);
    }

    /**
     * @throws TaskException
     */
    public function testCo3(): void
    {
        $data   = [
            'name',
            18306,
            'type'
        ];
        $result = $this->mockTaskServer->co('demoTestTask', 'method2', ['name', 18306]);
        $this->assertEquals($result, $data);

        $data   = [
            'name',
            18306,
            'defaultType'
        ];
        $result = $this->mockTaskServer->co('demoTestTask', 'method2', ['name', 18306, 'defaultType']);
        $this->assertEquals($result, $data);

        Context::getWaitGroup()->wait();
    }

    /**
     * @throws TaskException
     * @expectedException \Swoft\Task\Exception\TaskException
     */
    public function testCo6(): void
    {
        $this->mockTaskServer->co('demoTestTask', 'method3', ['name', 18306]);
    }

    public function testAsync(): void
    {
        //  $id = $this->mockTaskServer->async('demoTestTask', 'method2', ['name', 18306]);
        //  $this->assertGreaterThan(0, $id);
    }

    /**
     * @throws TaskException
     */
    public function testContext(): void
    {
        $data = [
            'unit1',
            1,
            'co',
            'demoTestTask',
            'method6',
            [
                'name',
                18306
            ],
        ];

        $result = $this->mockTaskServer->co('demoTestTask', 'method6', ['name', 18306]);
        $this->assertEquals($data, $result);
    }

    /**
     * @throws TaskException
     */
    public function testNotMapping(): void
    {
        $result = $this->mockTaskServer->co('demoTestTask', 'notMapping', []);
        $this->assertEquals($result, ['notMapping']);
    }

    /**
     * @throws TaskException
     */
    public function testBooReturn(): void
    {
        $result = $this->mockTaskServer->co('demoTestTask', 'booReturn', []);
        $this->assertTrue($result);
    }

    /**
     * @throws TaskException
     */
    public function testNullReturn(): void
    {
        $result = $this->mockTaskServer->co('demoTestTask', 'nullReturn', []);
        $this->assertNull($result);
    }

    /**
     * @throws TaskException
     */
    public function testVoidReturn(): void
    {
        $result = $this->mockTaskServer->co('demoTestTask', 'voidReturn2', []);
        $this->assertNull($result);
    }
}
