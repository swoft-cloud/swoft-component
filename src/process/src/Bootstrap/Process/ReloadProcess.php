<?php

/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Process\Bootstrap\Process;

use Swoft\App;
use Swoft\Process\Bean\Annotation\Process;
use Swoft\Process\Bootstrap\Reload;
use Swoft\Process\Process as SwoftProcess;
use Swoft\Process\ProcessInterface;

/**
 * Reload process
 *
 * @Process(name="reload", boot=true)
 */
class ReloadProcess implements ProcessInterface
{
    /**
     * @param \Swoft\Process\Process $process
     */
    public function run(SwoftProcess $process)
    {
        $processName = sprintf('%s reload process', App::$server->getPname());
        $process->name($processName);

        /* @var \Swoft\Process\Bootstrap\Reload $reload */
        $reload = App::getBean(Reload::class);
        $reload->run();
    }

    /**
     * @return bool
     */
    public function check(): bool
    {
        if (! App::getAppProperties()->get('server.server.autoReload', false)) {
            return false;
        }
        return true;
    }
}
