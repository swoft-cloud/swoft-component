<?php

namespace Swoft\Task\Bootstrap\Process;

use Swoft\App;
use Swoft\Process\Bean\Annotation\Process;
use Swoft\Process\Process as SwoftProcess;
use Swoft\Process\ProcessInterface;
use Swoft\Task\Crontab\Crontab;
use Swoft\Task\Helper\CronHelper;

/**
 * CronProduceProcess
 *
 * @Process(name="cron-producer", boot=true)
 */
class CronProduceProcess implements ProcessInterface
{
    public function run(SwoftProcess $process)
    {
        $pname = App::$server->getPname();
        $process->name(sprintf('%s cron-producer process', $pname));

        /* @var Crontab $crontab */
        $crontab = bean(Crontab::class);
        $crontab->consume();
    }

    public function check(): bool
    {
        return CronHelper::isCronable();
    }

}