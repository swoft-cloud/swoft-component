<?php declare(strict_types=1);

namespace Swoft\Http\Server\Command;

use Swoft\Console\Annotation\Mapping\Command;
use Swoft\Console\Annotation\Mapping\CommandMapping;
use Swoft\Console\Annotation\Mapping\CommandOption;
use Swoft\Console\Helper\Show;
use Swoft\Http\Server\HttpServer;
use Swoft\Server\Command\BaseServerCommand;

/**
 * Provide some commands to manage the HTTP Server
 *
 * @since 2.0
 *
 * @Command("http", alias="httpserver,httpServer,http-server", coroutine=false)
 * @example
 *  {fullCmd}:start     Start the http server
 *  {fullCmd}:stop      Stop the http server
 */
class HttpServerCommand extends BaseServerCommand
{
    /**
     * Start the http server
     *
     * @CommandMapping(usage="{fullCommand} [-d|--daemon]")
     * @CommandOption("daemon", short="d", desc="Run server on the background")
     *
     * @example
     *  {fullCommand}
     *  {fullCommand} -d
     *
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Server\Exception\ServerException
     */
    public function start(): void
    {
        $server = $this->createServer();

        // Check if it has started
        if ($server->isRunning()) {
            $masterPid = $server->getPid();
            \output()->writeln("<error>The server have been running!(PID: {$masterPid})</error>");
            return;
        }

        // Startup settings
        $this->configStartOption($server);

        $settings = $server->getSetting();
        // Setting
        $workerNum = $settings['worker_num'];

        // Server startup parameters
        $mainHost = $server->getHost();
        $mainPort = $server->getPort();
        $modeName = $server->getModeName();
        $typeName = $server->getTypeName();
        
        // TCP 启动参数
        // $tcpStatus = $server->getTcpSetting();

        Show::panel([
            'HTTP' => [
                'listen' => $mainHost . ':' . $mainPort,
                'type'   => $typeName,
                'mode'   => $modeName,
                'worker' => $workerNum,
            ],
        ]);

        \output()->writef('<success>Server start success !</success>');

        // Start the server
        $server->start();
    }

    /**
     * Reload worker processes
     *
     * @CommandMapping(usage="{fullCommand} [-t]")
     * @CommandOption("t", desc="Only to reload task processes, default to reload worker and task")
     *
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function reload(): void
    {
        $server = $this->createServer();
        $script = \input()->getScript();

        // Check if it has started
        if (!$server->isRunning()) {
            \output()->writeln('<error>The server is not running! cannot reload</error>');
            return;
        }

        \output()->writef('<info>Server %s is reloading</info>', $script);

        if ($reloadTask = input()->hasOpt('t')) {
            Show::notice('Will only reload task worker');
        }

        if (!$server->reload($reloadTask)) {
            Show::error('The swoole server worker process reload fail!');
            return;
        }

        \output()->writef('<success>Server %s reload success</success>', $script);
    }

    /**
     * Stop the currently running server
     *
     * @CommandMapping()
     *
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function stop(): void
    {
        $server = $this->createServer();

        // Check if it has started
        if (!$server->isRunning()) {
            \output()->writeln('<error>The server is not running! cannot stop.</error>');
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
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function restart(): void
    {
        $server = $this->createServer();

        // Check if it has started
        if ($server->isRunning()) {
            $server->stop();
        }

        \output()->writef('<success>Server reload success !</success>');
        $server->restart();
    }

    /**
     * @return HttpServer
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    private function createServer(): HttpServer
    {
        // check env
        // EnvHelper::check();
        $script = input()->getScript();
        /** @var HttpServer $server */
        $server = \bean('httpServer');
        $server->setScriptFile(\Swoft::app()->getPath($script));

        return $server;
    }
}
