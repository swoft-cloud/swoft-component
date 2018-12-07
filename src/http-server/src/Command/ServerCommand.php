<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Http\Server\Command;

use Swoft\Console\Bean\Annotation\Command;
use Swoft\Helper\EnvHelper;
use Swoft\Http\Server\Http\HttpServer;

/**
 * The group command list of HTTP-Server
 * @Command(coroutine=false, server=true)
 */
class ServerCommand
{
    /**
     * Start http server
     * @Usage {fullCommand} [-d|--daemon]
     *
     * @Options
     *   -d, --daemon    Run server on the background
     * @Example
     *   {fullCommand}
     *   {fullCommand} -d
     * @throws \InvalidArgumentException
     * @throws \Swoft\Exception\RuntimeException
     * @throws \RuntimeException
     */
    public function start()
    {
        $httpServer = $this->getHttpServer();

        // 是否正在运行
        if ($httpServer->isRunning()) {
            $serverStatus = $httpServer->getServerSetting();

            \output()->writeln("<error>The server have been running!(PID: {$serverStatus['masterPid']})</error>", true, true);
        } else {
            $serverStatus = $httpServer->getServerSetting();
        }

        $this->setStartArgs($httpServer);

        // Server Settings
        $httpStatus = $httpServer->getHttpSetting();
        $tcpStatus = $httpServer->getTcpSetting();

        $workerNum = $httpServer->setting['worker_num'];

        // HTTP Settings
        $httpHost = $httpStatus['host'];
        $httpPort = $httpStatus['port'];
        $httpMode = $httpStatus['mode'];
        $httpType = $httpStatus['type'];

        // TCP Settings
        $tcpEnable = $serverStatus['tcpable'];
        $tcpHost = $tcpStatus['host'];
        $tcpPort = $tcpStatus['port'];
        $tcpType = $tcpStatus['type'];

        // 信息面板
        $lines = [
            '                         Server Information                      ',
            '********************************************************************',
            "* HTTP | host: <note>$httpHost</note>, port: <note>$httpPort</note>, type: <note>$httpType</note>, worker: <note>$workerNum</note>, mode: <note>$httpMode</note>",
        ];
        $tcpEnable && $lines[] = "* TCP  | host: <note>$tcpHost</note>, port: <note>$tcpPort</note>, type: <note>$tcpType</note>, worker: <note>$workerNum</note>";
        $lines[] = '********************************************************************';

        // 启动服务器
        \output()->writeln(implode("\n", $lines));
        $httpServer->start();
    }

    /**
     * Reload worker processes
     * @Usage {fullCommand} [-t]
     *
     * @Options
     *  -t      Only to reload task processes, default to reload worker and task
     * @Example {fullCommand}
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function reload()
    {
        $httpServer = $this->getHttpServer();

        if (! $httpServer->isRunning()) {
            output()->writeln('<error>The server is not running! cannot reload</error>', true, true);
        }

        output()->writeln(sprintf('<info>Server %s is reloading</info>', input()->getScript()));

        $reloadTask = input()->hasOpt('t');
        $httpServer->reload($reloadTask);
        output()->writeln(sprintf('<success>Server %s reload success</success>', input()->getScript()));
    }

    /**
     * Stop the http server
     * @Usage {fullCommand}
     * @Example {fullCommand}
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function stop()
    {
        $httpServer = $this->getHttpServer();

        if (! $httpServer->isRunning()) {
            \output()->writeln('<error>The server is not running! cannot stop</error>', true, true);
        }

        $serverStatus = $httpServer->getServerSetting();
        $pidFile = $serverStatus['pfile'];

        \output()->writeln(sprintf('<info>Swoft %s is stopping ...</info>', input()->getScript()));

        $result = $httpServer->stop();

        if (! $result) {
            \output()->writeln(sprintf('<error>Swoft %s stop fail</error>', input()->getScript()), true, true);
        }
        @unlink($pidFile);

        output()->writeln(sprintf('<success>Swoft %s stop success!</success>', input()->getScript()));
    }

    /**
     * Restart the http server
     * @Usage {fullCommand} [-d|--daemon]
     *
     * @Options
     *   -d, --daemon    Run server on the background
     * @Example
     *   {fullCommand}
     *   {fullCommand} -d
     * @throws \Swoft\Exception\RuntimeException
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function restart()
    {
        $httpServer = $this->getHttpServer();

        if ($httpServer->isRunning()) {
            $this->stop();
        }

        // Daemon mode is defaults for `restart` action
        $httpServer->setDaemonize();
        $this->start();
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    private function getHttpServer(): HttpServer
    {
        EnvHelper::check();

        return (new HttpServer())->setScriptFile(input()->getScript());
    }

    /**
     * 设置启动选项，覆盖 config/server.php 配置选项
     */
    private function setStartArgs(HttpServer $httpServer)
    {
        $daemonize = \input()->getSameOpt(['d', 'daemon'], false);
        $daemonize && $httpServer->setDaemonize();
    }
}
