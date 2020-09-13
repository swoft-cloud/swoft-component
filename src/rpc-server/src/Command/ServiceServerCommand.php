<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Rpc\Server\Command;

use Swoft;
use Swoft\Console\Annotation\Mapping\Command;
use Swoft\Console\Annotation\Mapping\CommandMapping;
use Swoft\Console\Annotation\Mapping\CommandOption;
use Swoft\Rpc\Server\ServiceServer;
use Swoft\Server\Command\BaseServerCommand;
use Swoft\Server\Exception\ServerException;
use function bean;
use function input;
use function output;

/**
 * Class ServiceServerCommand
 *
 * @since 2.0
 *
 * @Command("rpc", coroutine=false, desc="Provide some commands to manage swoft RPC server")
 *
 * @example
 *  {groupName}:start     Start the rpc server
 *  {groupName}:stop      Stop the rpc server
 */
class ServiceServerCommand extends BaseServerCommand
{
    /**
     * Start the http server
     *
     * @CommandMapping(usage="{fullCommand} [-d|--daemon]")
     * @CommandOption("daemon", short="d", desc="Run server on the background")
     *
     * @throws ServerException
     * @example
     *  {fullCommand}
     *  {fullCommand} -d
     *
     */
    public function start(): void
    {
        $server = $this->createServer();

        $this->showServerInfoPanel($server);

        // Start the server
        $server->start();
    }

    /**
     * Reload worker processes
     *
     * @CommandMapping(usage="{fullCommand} [-t]")
     * @CommandOption("t", desc="Only to reload task processes, default to reload worker and task")
     */
    public function reload(): void
    {
        $server = $this->createServer();

        // Reload server
        $this->reloadServer($server);
    }

    /**
     * Stop the currently running server
     *
     * @CommandMapping()
     *
     */
    public function stop(): void
    {
        $server = $this->createServer();

        // Check if it has started
        if (!$server->isRunning()) {
            output()->writeln('<error>The RPC server is not running! cannot stop.</error>');
            return;
        }

        // Do stopping.
        $server->stop();
    }

    /**
     * Restart the http server
     *
     * @CommandMapping(usage="{fullCommand} [-d|--daemon]",)
     * @CommandOption("daemon", short="d", desc="Run server on the background")
     *
     * @example
     *  {fullCommand}
     *  {fullCommand} -d
     */
    public function restart(): void
    {
        $server = $this->createServer();

        // Restart server
        $this->restartServer($server);
    }

    /**
     * @return ServiceServer
     */
    private function createServer(): ServiceServer
    {
        $script  = input()->getScriptFile();
        $command = $this->getFullCommand();

        /** @var ServiceServer $server */
        $server = bean('rpcServer');
        $server->setScriptFile(Swoft::app()->getPath($script));
        $server->setFullCommand($command);

        return $server;
    }
}
