<?php declare(strict_types=1);


namespace SwoftTest\Task\Testing;

use Swoft\Task\Annotation\Mapping\Task;
use Swoft\Task\Annotation\Mapping\TaskMapping;
use Swoft\Task\Exception\TaskException;

/**
 * Class DemoTask
 *
 * @since 2.0
 *
 * @Task(name="demoTestTask")
 */
class DemoTask
{
    /**
     * @param string $name
     * @param int    $count
     *
     * @return array
     *
     * @TaskMapping("method")
     */
    public function method(string $name, int $count): array
    {
        return [$name, $count];
    }

    /**
     * @param string $name
     * @param int    $count
     * @param string $type
     *
     * @return array
     *
     * @TaskMapping("method2")
     */
    public function method2(string $name, int $count, string $type = 'type'): array
    {
        return [$name, $count, $type];
    }

    /**
     * @param string $name
     * @param int    $count
     * @param string $type
     *
     * @return array
     *
     * @TaskMapping(name="method3")
     *
     * @throws TaskException
     */
    public function method3(string $name, int $count, string $type = 'type'): array
    {
        throw new TaskException('ExceptionTest');
    }
}