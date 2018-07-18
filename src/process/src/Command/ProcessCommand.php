<?php

namespace Swoft\Process\Command;

use Swoft\Console\Bean\Annotation\Command;
use Swoft\Helper\EnvHelper;
use Swoft\Process\ProcessPool;

/**
 * The group command list of Process Pool
 *
 * @Command(coroutine=false,server=true)
 */
class ProcessCommand
{
    /**
     * Start Process Pool
     *
     * @Usage {fullCommand} [-d|--daemon]
     *
     * @Options
     *   -d, --daemon    Run server on the background
     * @Example
     *   {fullCommand}
     *   {fullCommand} -d
     */
    public function start()
    {
        $processPool = $this->getProcessPool();
        $processPool->start();
    }

    /**
     * @return \Swoft\Process\ProcessPool
     */
    private function getProcessPool(): ProcessPool
    {
        EnvHelper::check();

        // Script init
        $script = input()->getScript();

        $processPool = new ProcessPool();
        $processPool->setScriptFile($script);

        return $processPool;
    }
}