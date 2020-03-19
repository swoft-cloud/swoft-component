<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\WebSocket\Server\Command;

use Swoft\Console\Annotation\Mapping\Command;
use Swoft\Console\Annotation\Mapping\CommandMapping;
use Swoft\Console\Annotation\Mapping\CommandOption;
use Swoft\Server\Command\BaseServerCommand;
use Swoft\Server\Exception\ServerException;
use Swoft\Server\Server;
use Swoft\Server\SwooleEvent;
use Swoft\WebSocket\Server\WebSocketServer;
use Swoft\WebSocket\Server\WsServerBean;
use Throwable;
use function bean;
use function input;
use function output;

/**
 * Class WsServerCommand
 *
 * @Command("ws",
 *     coroutine=false,
 *     alias="wsserver,websocket",
 *     desc="provide some commands to manage swoft websocket server"
 * )
 */
class WsServerCommand extends BaseServerCommand
{
    /**
     * Start the websocket server
     *
     * @CommandMapping(usage="{fullCommand} [-d|--daemon]")
     * @CommandOption("daemon", short="d", desc="Run server on the background", default="false", type="bool")
     *
     * @throws ServerException
     * @throws Throwable
     * @example
     *   {fullCommand}
     *   {fullCommand} -d  Start server on background
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
     * @param Server $server
     *
     * @return array
     */
    protected function buildMainServerInfo(Server $server): array
    {
        $info = parent::buildMainServerInfo($server);

        $openHttp = $server->hasListener(SwooleEvent::REQUEST);

        $info['HTTP'] = $openHttp ? 'Enabled' : 'Disabled';
        return $info;
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
            output()->writeln('<error>The server is not running! cannot stop.</error>');
            return;
        }

        // Do stopping.
        $server->stop();
    }

    /**
     * Restart the websocket server
     *
     * @CommandMapping(usage="{fullCommand} [-d|--daemon]")
     * @CommandOption("daemon", short="d", desc="Run server on the background")
     *
     * @example
     * {fullCommand}
     * {fullCommand} -d
     */
    public function restart(): void
    {
        $server = $this->createServer();

        // Restart server
        $this->restartServer($server);
    }

    /**
     * @return WebSocketServer
     */
    private function createServer(): WebSocketServer
    {
        $script  = input()->getScriptFile();
        $command = $this->getFullCommand();

        /* @var WebSocketServer $server */
        $server = bean(WsServerBean::SERVER);
        $server->setScriptFile($script);
        $server->setFullCommand($command);

        return $server;
    }
}
