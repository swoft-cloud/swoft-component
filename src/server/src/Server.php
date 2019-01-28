<?php

namespace Swoft\Server;

use Co\Server as CoServer;
use Swoft\Server\Exception\ServerException;
use Swoft\Server\Swoole\SwooleEvent;
use Swoft\Stdlib\Helper\Sys;

/**
 * Class Server
 *
 * @since 2.0
 */
abstract class Server implements ServerInterface
{
    /**
     * Swoft server
     *
     * @var Server
     */
    protected static $server;

    /**
     * Default host
     *
     * @var string
     */
    protected $host = '0.0.0.0';

    /**
     * Default port
     *
     * @var int
     */
    protected $port = 88;

    /**
     * Default mode
     *
     * @var int
     */
    protected $mode = SWOOLE_PROCESS;

    /**
     * Default socket type
     *
     * @var int
     */
    protected $type = SWOOLE_SOCK_TCP;

    /**
     * Server setting for swoole settting
     *
     * @var array
     */
    protected $setting = [];

    /**
     * Pid file
     *
     * @var string
     */
    protected $pidFille = '@runtime/swoft.pid';

    /**
     * Pid name
     *
     * @var string
     */
    protected $pidName = 'swoft';

    /**
     * Server event for swoole event
     *
     * @var array
     *
     * @example
     * [
     *     'serverName' => new SwooleEventListener(),
     *     'serverName' => new SwooleEventListener(),
     *     'serverName' => new SwooleEventListener(),
     * ]
     */
    protected $on = [];

    /**
     * Script file
     *
     * @var string
     */
    protected $scriptFile = '';

    /**
     * Swoole Server
     *
     * @var \Co\Server
     */
    protected $swooleServer;

    /**
     * Server constructor
     */
    public function __construct()
    {
        self::$server = $this;

        // Init
        $this->init();
    }

    /**
     * Init
     */
    public function init(): void
    {

    }

    /**
     * On start event
     *
     * @param CoServer $server
     *
     * @return void
     */
    protected function onStart(CoServer $server): void
    {
        $pidFile = alias($this->pidFille);
        $pidStr  = sprintf('%s,%s', $server->master_pid, $server->manager_pid);
        $title   = sprintf('%s master process (%s)', $this->pidName, $this->scriptFile);

        \file_put_contents($pidFile, $pidStr);
        Sys::setProcessTitle($title);
    }

    /**
     * Manager start event
     *
     * @param CoServer $server
     */
    protected function onManagerStart(CoServer $server): void
    {
        Sys::setProcessTitle(sprintf('%s manager process', $this->pidName));
    }

    /**
     * Manager stop event
     *
     * @param CoServer $server
     */
    protected function onManagerStop(CoServer $server): void
    {

    }

    /**
     * Worker start event
     *
     * @param CoServer $server
     * @param int      $workerId
     */
    protected function onWorkerStart(CoServer $server, int $workerId): void
    {
        // Init Worker and TaskWorker
        $setting = $server->setting;

        // TaskWorker
        if ($workerId >= $setting['worker_num']) {
            Sys::setProcessTitle(sprintf('%s task process'));
            return;
        }

        // Worker
        Sys::setProcessTitle(sprintf('%s worker process'));

    }

    /**
     * Worker stop
     *
     * @param CoServer $server
     * @param int      $workerId
     */
    protected function onWorkerStop(CoServer $server, int $workerId): void
    {

    }

    /**
     * Worker error stop
     *
     * @param CoServer $server
     * @param int      $workerId
     * @param int      $workerPid
     * @param int      $exitCode
     * @param int      $signa
     */
    protected function onWorkerError(CoServer $server, int $workerId, int $workerPid, int $exitCode, int $signa): void
    {

    }

    /**
     * Shutdown event
     *
     * @param CoServer $server
     */
    protected function onShutdown(CoServer $server): void
    {

    }

    /**
     * Bind swoole event
     */
    protected function startSwoole(): void
    {
        if (empty($this->swooleServer)) {
            throw new ServerException('You must to new server before start swoole!');
        }

        $this->swooleServer->set($this->setting);
        foreach ($this->on as $name => $listener) {
            if (!isset(SwooleEvent::LISTENER_MAPPING[$name])) {
                throw new ServerException(sprintf('Swoole %s event is not defined!', $name));
            }

            $listenerInterface = SwooleEvent::LISTENER_MAPPING[$name];
            if (!($listener instanceof $listenerInterface)) {
                throw new ServerException(sprintf('Swoole %s event listener is not %s', $name, $listenerInterface));
            }

            $listenerMethod = sprintf('on%s', ucfirst($name));
            $this->swooleServer->on($name, [$listener, $listenerMethod]);
        }

        $this->swooleServer->start();
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @return int
     */
    public function getMode(): int
    {
        return $this->mode;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getSetting(): array
    {
        return $this->setting;
    }

    /**
     * @return array
     */
    public function getOn(): array
    {
        return $this->on;
    }

    /**
     * @param string $scriptFile
     */
    public function setScriptFile(string $scriptFile): void
    {
        $this->scriptFile = $scriptFile;
    }

    /**
     * @return Server
     */
    public static function getServer(): Server
    {
        return self::$server;
    }

    /**
     * @param Server $server
     */
    public static function setServer(Server $server): void
    {
        self::$server = $server;
    }

    public function restart(): void
    {
        // TODO: Implement restart() method.
    }

    public function stop(): void
    {
        // TODO: Implement stop() method.
    }
}