<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Process\Command;

use Swoft;
use Swoft\Console\Annotation\Mapping\Command;
use Swoft\Console\Annotation\Mapping\CommandMapping;
use Swoft\Console\Annotation\Mapping\CommandOption;
use Swoft\Console\Helper\Show;
use Swoft\Process\Exception\ProcessException;
use Swoft\Process\ProcessPool;
use Swoft\Server\Command\BaseServerCommand;

/**
 * Class ProcessCommand
 *
 * @since 2.0
 *
 * @Command(name="process", coroutine=false, desc="Provides some command for manage process pool")
 * @example
 *  {fullCmd}:start     Start the process pool
 *  {fullCmd}:stop      Stop the process pool
 */
class ProcessCommand extends BaseServerCommand
{
    /**
     * @CommandMapping(desc="start the process pool")
     * @CommandOption("daemon", short="d", desc="Run server on the background", type="bool", default="false")
     *
     * @throws ProcessException
     * @example
     *   {fullCommand}
     *   {fullCommand} -d
     */
    public function start(): void
    {
        $server = $this->createServer();

        // Check if it has started
        if ($server->isRunning()) {
            $masterPid = $server->getPid();
            output()->writeln("<error>The Process pool have been running!(PID: {$masterPid})</error>");
            return;
        }

        // Daemon
        $asDaemon = input()->getSameOpt(['d', 'daemon'], false);
        if ($asDaemon) {
            $server->setDaemonize();
        }

        $server->start();
    }

    /**
     * @CommandMapping(desc="restart the process pool")
     *
     * @throws ProcessException
     */
    public function restart(): void
    {
        $server = $this->createServer();

        // Check if it has started
        if ($server->isRunning()) {
            $success = $server->stop();
            if (!$success) {
                output()->error('Stop the old process pool failed!');
                return;
            }
        }

        output()->writef('<success>Process pool restart success !</success>');

        $server->setDaemonize();
        $server->start();
    }

    /**
     * @CommandMapping(desc="reload the process pool's worker")
     */
    public function reload(): void
    {
        $server = $this->createServer();
        $script = input()->getScriptFile();

        // Check if it has started
        if (!$server->isRunning()) {
            output()->writeln('<error>The Process pool is not running! cannot reload</error>');
            return;
        }

        output()->writef('<info>Server %s is reloading</info>', $script);

        if (!$server->reload()) {
            Show::error('The process pool worker process reload fail!');
            return;
        }

        output()->writef('<success>Process pool %s reload success</success>', $script);
    }

    /**
     * @CommandMapping(desc="stop the process pool")
     */
    public function stop(): void
    {
        $server = $this->createServer();

        // Check if it has started
        if (!$server->isRunning()) {
            output()->writeln('<error>The Process pool is not running! cannot stop.</error>');
            return;
        }

        // Do stopping.
        $server->stop();
    }

    /**
     * @return ProcessPool
     */
    private function createServer(): ProcessPool
    {
        $script  = input()->getScriptFile();
        $command = $this->getFullCommand();

        /** @var ProcessPool $processPool */
        $processPool = bean('processPool');
        $processPool->setScriptFile(Swoft::app()->getPath($script));
        $processPool->setFullCommand($command);

        return $processPool;
    }
}
