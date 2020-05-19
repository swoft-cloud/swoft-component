<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Http\Server\Command;

use Swoft;
use Swoft\Console\Annotation\Mapping\Command;
use Swoft\Console\Annotation\Mapping\CommandMapping;
use Swoft\Console\Annotation\Mapping\CommandOption;
use Swoft\Http\Server\HttpServer;
use Swoft\Server\Command\BaseServerCommand;
use Swoft\Server\Exception\ServerException;
use function bean;
use function input;
use function output;

/**
 * Provide some commands to manage the swoft HTTP server
 *
 * @since 2.0
 *
 * @Command("http", alias="httpsrv", coroutine=false)
 * @example
 *  {groupName}:start     Start the http server
 *  {groupName}:stop      Stop the http server
 */
class HttpServerCommand extends BaseServerCommand
{
    /**
     * Start the http server
     *
     * @CommandMapping(usage="{fullCommand} [-d|--daemon]")
     * @CommandOption("daemon", short="d", desc="Run server on the background", type="bool", default="false")
     *
     * @throws ServerException
     * @example
     *   {fullCommand}
     *   {fullCommand} -d
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
     */
    public function stop(): void
    {
        $server = $this->createServer();

        // Check if it has started
        if (!$server->isRunning()) {
            output()->writeln('<error>The HTTP server is not running! cannot stop.</error>');
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
     * @return HttpServer
     */
    private function createServer(): HttpServer
    {
        $script  = input()->getScriptFile();
        $command = $this->getFullCommand();

        /** @var HttpServer $server */
        $server = bean('httpServer');
        $server->setScriptFile(Swoft::app()->getPath($script));
        $server->setFullCommand($command);

        return $server;
    }
}
