<?php declare(strict_types=1);


namespace SwoftTest\Task\Unit;

use Swoft\Task\Exception\TaskException;

/**
 * Class TaskTest
 *
 * @since 2.0
 */
class TaskTest extends TestCase
{
    /**
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Task\Exception\TaskException
     *
     * @expectedException \Swoft\Task\Exception\TaskException
     */
    public function testCo()
    {
        $this->mockTaskServer->co('demoTestTask', 'method', []);
    }

    /**
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Task\Exception\TaskException
     */
    public function testCo2()
    {
        $data   = [
            'name',
            18306
        ];
        $result = $this->mockTaskServer->co('demoTestTask', 'method', ['name', 18306]);
        $this->assertEquals($result, $data);
    }

    /**
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Task\Exception\TaskException
     */
    public function testCo3()
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
    }

    /**
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Task\Exception\TaskException
     * @expectedException \Swoft\Task\Exception\TaskException
     */
    public function testCo6()
    {
        $this->mockTaskServer->co('demoTestTask', 'method3', ['name', 18306]);
    }

    /**
     * @throws TaskException
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function testAsync()
    {
        $id = $this->mockTaskServer->async('demoTestTask', 'method2', ['name', 18306]);
        $this->assertGreaterThan(0, $id);

        \Swoole\Event::wait();
    }
}