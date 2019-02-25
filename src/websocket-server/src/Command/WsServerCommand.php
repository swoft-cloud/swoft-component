<?php

namespace Swoft\WebSocket\Server\Command;

use Swoft\Console\Annotation\Mapping\Command;
use Swoft\Console\Annotation\Mapping\CommandMapping;
use Swoft\Console\Annotation\Mapping\CommandOption;
use Swoft\Console\Helper\Show;
use Swoft\Helper\EnvHelper;
use Swoft\Server\Server;
use Swoft\WebSocket\Server\WebSocketServer;

/**
 * Class WsServerCommand
 * @Command("http", coroutine=false, desc="provide some commands to operate WebSocket Server")
 */
class WsServerCommand
{
    /**
     * Start the webSocket server
     *
     * @CommandMapping(
     *     usage="{fullCommand} [-d|--daemon]",
     *     example="{fullCommand}\n{fullCommand} -d"
     * )
     * @CommandOption("daemon", short="d", desc="Run server on the background")
     *
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Server\Exception\ServerException
     */
    public function start()
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
        $tcpStatus = [];
        $tcpEnable = $serverStatus['tcpable'] ?? false;
        $tcpHost = $tcpStatus['host'] ?? 'unknown';
        $tcpPort = $tcpStatus['port'] ?? 'unknown';
        $tcpType = $tcpStatus['type'] ?? 'unknown';
        $tcpEnable = $tcpEnable ? '<info>Enabled</info>' : '<warning>Disabled</warning>';
        // 信息面板
        $lines = [
            '                         Server Information                      ',
            '********************************************************************',
            "* HTTP | host: <note>$mainHost</note>, port: <note>$mainPort</note>, type: <note>$typeName</note>, worker: <note>$workerNum</note>, mode: <note>$modeName</note>",
            "* TCP  | host: <note>$tcpHost</note>, port: <note>$tcpPort</note>, type: <note>$tcpType</note>, worker: <note>$workerNum</note> ($tcpEnable)",
            '********************************************************************',
        ];

        \output()->writeln(implode("\n", $lines));

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
     * @CommandMapping(
     *     usage="{fullCommand} [-d|--daemon]",
     *     example="
     * {fullCommand}
     * {fullCommand} -d"
     * )
     * @CommandOption("daemon", short="d", desc="Run server on the background")
     *
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function restart(): void
    {
        $server = $this->createServer();

        // Restart server
        $server->restart();
    }

    /**
     * @return WebSocketServer
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    private function createServer(): WebSocketServer
    {
        // check env
        // EnvHelper::check();

        // http server初始化
        $script = input()->getScript();

        $server = \bean('wsServer');
        $server->setScriptFile($script);

        return $server;
    }

    /**
     * 设置启动选项，覆盖配置选项
     *
     * @param Server $server
     */
    protected function configStartOption(Server $server): void
    {
        $asDaemon = \input()->getSameOpt(['d', 'daemon'], false);

        if ($asDaemon) {
            $server->setDaemonize();
        }
    }
}

