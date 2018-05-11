<?php

namespace Swoft\Task\Bootstrap\Process;

use Swoft\App;
use Swoft\Process\Bean\Annotation\Process;
use Swoft\Process\Process as SwoftProcess;
use Swoft\Process\ProcessInterface;
use Swoft\Task\Crontab\Crontab;
use Swoft\Task\Helper\CronHelper;

/**
 * CronConsumeProcess
 *
 * @Process(name="cron-consumer", boot=true)
 */
class CronConsumeProcess implements ProcessInterface
{
    public function run(SwoftProcess $process)
    {
        $pname = App::$server->getPname();
        $process->name(sprintf('%s cron-consumer process', $pname));

        /* @var Crontab $crontab */
        $crontab = bean(Crontab::class);
        $crontab->produce();
    }

    public function check(): bool
    {
        return CronHelper::isCronable();
    }

}