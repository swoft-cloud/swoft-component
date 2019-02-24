<?php

namespace Swoft\Http\Server\Command;

use Swoft\Console\Annotation\Mapping\Command;
use Swoft\Console\Annotation\Mapping\CommandMapping;
use Swoft\Console\Annotation\Mapping\CommandOption;
use Swoft\Console\Helper\Show;
use Swoft\Http\Server\HttpServer;

/**
 * Class HttpServerCommand
 * @Command("http", coroutine=false, desc="provide some commands to operate HTTP Server")
 */
class HttpServerCommand
{
    /**
     * Start http server
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
    public function start(): void
    {
        $server = $this->getHttpServer();

        // 是否正在运行
        if ($server->isRunning()) {
            $masterPid = $server->getPid();
            \output()->writeln("<error>The server have been running!(PID: {$masterPid})</error>");
            return;
        }

        // 启动参数
        $this->setStartArgs($server);

        $settings = $server->getSetting();
        // Setting
        $workerNum = $settings['worker_num'];

        // HTTP 启动参数
        $httpHost = $server->getHost();
        $httpPort = $server->getPort();
        $httpMode = $server->getModeName();
        $httpType = $server->getTypeName();

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
            "* HTTP | host: <note>$httpHost</note>, port: <note>$httpPort</note>, type: <note>$httpType</note>, worker: <note>$workerNum</note>, mode: <note>$httpMode</note>",
            "* TCP  | host: <note>$tcpHost</note>, port: <note>$tcpPort</note>, type: <note>$tcpType</note>, worker: <note>$workerNum</note> ($tcpEnable)",
            '********************************************************************',
        ];

        // 启动服务器
        \output()->writeln(implode("\n", $lines));

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
        $server = $this->getHttpServer();
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

        $server->reload($reloadTask);

        \output()->writef('<success>Server %s reload success</success>', $script);
    }

    /**
     * Stop the http server
     *
     * @CommandMapping()
     *
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function stop(): void
    {
        $server = $this->getHttpServer();

        // Check if it has started
        if (!$server->isRunning()) {
            \output()->writeln('<error>The server is not running! cannot stop.</error>');
            return;
        }

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
     * @throws \Swoft\Server\Exception\ServerException
     */
    public function restart(): void
    {
        $server = $this->getHttpServer();

        // Check if it has started
        if ($server->isRunning()) {
            $this->stop();
        }

        // 重启默认是守护进程
        $server->setDaemonize();
        // Start server
        $this->start();
    }

    /**
     * @return HttpServer
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    private function getHttpServer(): HttpServer
    {
        // check env
        // EnvHelper::check();
        // http server初始化
        $server = \bean('httpServer');
        $script = input()->getScript();
        $server->setScriptFile($script);

        return $server;
    }

    /**
     * 设置启动选项，覆盖配置选项
     *
     * @param HttpServer $server
     */
    protected function setStartArgs(HttpServer $server): void
    {
        $asDaemon = \input()->getSameOpt(['d', 'daemon'], false);

        if ($asDaemon) {
            $server->setDaemonize();
        }
    }
}
