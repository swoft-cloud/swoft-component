<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Task\Testing;

use Swoft\Task\Annotation\Mapping\Task;
use Swoft\Task\Annotation\Mapping\TaskMapping;
use Swoft\Task\Exception\TaskException;
use function context;

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

    /**
     * @param string $name
     * @param int    $count
     *
     * @return array
     *
     * @throws \Swoft\Exception\SwoftException
     * @TaskMapping(name="method6")
     */
    public function method6(string $name, int $count): array
    {
        $request = context()->getRequest();

        return [
            $request->getTaskUniqid(),
            $request->getTaskId(),
            $request->getType(),
            $request->getName(),
            $request->getMethod(),
            $request->getParams(),
        ];
    }

    /**
     * @TaskMapping()
     *
     * @return array
     */
    public function notMapping(): array
    {
        return [
            'notMapping'
        ];
    }

    /**
     * @TaskMapping()
     *
     * @return bool
     */
    public function booReturn(): bool
    {
        return true;
    }

    /**
     * @TaskMapping()
     *
     * @return null
     */
    public function nullReturn()
    {
        return null;
    }

    /**
     * @TaskMapping()
     */
    public function voidReturn2(): void
    {
        return;
    }
}
