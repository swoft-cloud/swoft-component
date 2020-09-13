<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Process;

use Swoft\Helper\ComposerJSON;
use Swoft\Process\Swoole\WorkerStartListener;
use Swoft\Process\Swoole\WorkerStopListener;
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
     */
    public function beans(): array
    {
        return [
            'processPool' => [
                'class' => ProcessPool::class,
                'on'    => [
                    SwooleEvent::WORKER_START => bean(WorkerStartListener::class),
                    SwooleEvent::WORKER_STOP  => bean(WorkerStopListener::class)
                ],
                'workerNum' => 2,
            ]
        ];
    }

    /**
     * @return array
     */
    public function metadata(): array
    {
        $jsonFile = dirname(__DIR__) . '/composer.json';

        return ComposerJSON::open($jsonFile)->getMetadata();
    }
}
