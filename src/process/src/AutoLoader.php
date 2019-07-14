<?php declare(strict_types=1);


namespace Swoft\Process;


use ReflectionException;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Process\Swoole\WorkerStartListener;
use Swoft\SwoftComponent;

/**
 * Class AutoLoader
 *
 * @since 2.0
 */
class AutoLoader extends SwoftComponent
{
    /**
     * @return array
     */
    public function getPrefixDirs(): array
    {
        return [
            __NAMESPACE__ => __DIR__,
        ];
    }

    /**
     * @return array
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function beans(): array
    {
        return [
            'processPool' => [
                'class' => ProcessPool::class,
                'on'    => [
                    SwooleEvent::WORKER_START => bean(WorkerStartListener::class)
                ]
            ]
        ];
    }

    /**
     * @return array
     */
    public function metadata(): array
    {
        return [];
    }
}