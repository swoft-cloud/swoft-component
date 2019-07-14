<?php declare(strict_types=1);


namespace Swoft\Process\Command;

use ReflectionException;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Console\Annotation\Mapping\Command;
use Swoft\Console\Annotation\Mapping\CommandMapping;
use Swoft\Process\Exception\ProcessException;
use Swoft\Process\Process;
use Swoft\Process\ProcessPool;
use Swoft\Server\Command\BaseServerCommand;

/**
 * Class ProcessCommand
 *
 * @since 2.0
 *
 * @Command(name="process", coroutine=false)
 * @example
 *  {fullCmd}:start     Start the process pool
 *  {fullCmd}:stop      Stop the process pool
 */
class ProcessCommand extends BaseServerCommand
{
    /**
     * @CommandMapping()
     *
     * @throws ContainerException
     * @throws ReflectionException
     * @throws ProcessException
     */
    public function start(): void
    {
        $server = $this->createServer();

        Process::daemon();

        // Check if it has started
        if ($server->isRunning()) {
            $masterPid = $server->getPid();
            output()->writeln("<error>The Process pool have been running!(PID: {$masterPid})</error>");
            return;
        }

        $server->start();
    }

    /**
     * @CommandMapping()
     */
    public function restart(): void
    {

    }

    /**
     * @CommandMapping()
     */
    public function stop(): void
    {

    }

    /**
     * @return ProcessPool
     * @throws ReflectionException
     * @throws ContainerException
     */
    private function createServer(): ProcessPool
    {
        $script  = input()->getScript();
        $command = $this->getFullCommand();

        /** @var ProcessPool $processPool */
        $processPool = bean('processPool');
        $processPool->setScriptFile(\Swoft::app()->getPath($script));
        $processPool->setFullCommand($command);

        return $processPool;
    }
}