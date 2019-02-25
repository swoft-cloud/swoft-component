<?php

namespace Swoft\Server;

use Co\Server as CoServer;
use Swoft\Server\Exception\ServerException;
use Swoft\Server\Helper\ServerHelper;
use Swoft\Server\Swoole\SwooleEvent;
use Swoft\Stdlib\Helper\Sys;
use Swoole\Process;

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
     * Server type name. eg: http, ws, tcp ...
     *
     * @var string
     */
    protected static $serverType = 'TCP';

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
    protected $port = 9088;

    /**
     * Default mode
     *
     * @var int
     */
    protected $mode = \SWOOLE_PROCESS;

    /**
     * Default socket type
     *
     * @var int
     */
    protected $type = \SWOOLE_SOCK_TCP;

    /**
     * Server setting for swoole. (@see swooleServer->setting)
     *
     * @var array
     */
    protected $setting = [
        'worker_num' => 1,
    ];

    /**
     * Pid file
     *
     * @var string
     */
    protected $pidFile = '@runtime/swoft.pid';

    /**
     * Pid name
     *
     * @var string
     */
    protected $pidName = 'swoft';

    /**
     * Record started server master/manager PIDs
     *
     * @var array
     */
    protected $pidMap = [
        'master'  => 0,
        'manager' => 0,
    ];

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
     * @var \Co\Server|\Co\Http\Server|\Co\Websocket\Server
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
     * @return string
     */
    public function getServerType(): string
    {
        return static::$serverType;
    }

    /**
     * On master start event
     *
     * @param CoServer $server
     *
     * @return void
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    protected function onStart(CoServer $server): void
    {
        $masterPid  = $server->master_pid;
        $managerPid = $server->manager_pid;

        // save to property
        $this->pidMap = [
            'master'  => $masterPid,
            'manager' => $managerPid,
        ];

        $pidStr = \sprintf('%s,%s', $masterPid, $managerPid);
        $title  = \sprintf('%s master process (%s)', $this->pidName, $this->scriptFile);

        \file_put_contents(\alias($this->pidFile), $pidStr);

        // set process title
        Sys::setProcessTitle($title);

        \Swoft::trigger(SwooleEvent::START, $server->master_pid, $server);
    }

    /**
     * Manager start event
     *
     * @param CoServer $server
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    protected function onManagerStart(CoServer $server): void
    {
        Sys::setProcessTitle(\sprintf('%s manager process', $this->pidName));

        \Swoft::trigger(SwooleEvent::MANAGER_START, $server->manager_pid, $server);
    }

    /**
     * Manager stop event
     *
     * @param CoServer $server
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    protected function onManagerStop(CoServer $server): void
    {
        \Swoft::trigger(SwooleEvent::MANAGER_STOP, null, $server);
    }

    /**
     * Worker start event
     *
     * @param CoServer $server
     * @param int      $workerId
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    protected function onWorkerStart(CoServer $server, int $workerId): void
    {
        \Swoft::trigger(SwooleEvent::WORKER_START, $workerId, $server);

        // Init Worker and TaskWorker
        $setting = $server->setting;

        // Task process
        if ($workerId >= $setting['worker_num']) {
            Sys::setProcessTitle(sprintf('%s task process', $workerId));
            \Swoft::trigger(ServerEvent::TASK_PROCESS_START, $workerId, $server);

            return;
        }

        // Worker process
        Sys::setProcessTitle(sprintf('%s worker process', $workerId));
        \Swoft::trigger(ServerEvent::WORK_PROCESS_START, $workerId, $server);
    }

    /**
     * Worker stop event
     *
     * @param CoServer $server
     * @param int      $workerId
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    protected function onWorkerStop(CoServer $server, int $workerId): void
    {
        \Swoft::trigger(SwooleEvent::WORKER_STOP, $workerId, $server);
    }

    /**
     * Worker error stop event
     *
     * @param CoServer $server
     * @param int      $workerId
     * @param int      $workerPid
     * @param int      $exitCode
     * @param int      $signal
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    protected function onWorkerError(CoServer $server, int $workerId, int $workerPid, int $exitCode, int $signal): void
    {
        \Swoft::trigger(SwooleEvent::WORKER_ERROR, $workerId, $server, $workerPid, $exitCode, $signal);
    }

    /**
     * Shutdown event
     *
     * @param CoServer $server
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    protected function onShutdown(CoServer $server): void
    {
        \Swoft::trigger(SwooleEvent::SHUTDOWN, null, $server);
    }

    /**
     * Bind swoole event and start swoole server
     * @throws ServerException
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    protected function startSwoole(): void
    {
        if ($this->swooleServer === null) {
            throw new ServerException('You must to new server before start swoole!');
        }

        \Swoft::trigger(ServerEvent::BEFORE_SETTING);

        // set settings
        $this->swooleServer->set($this->setting);

        \Swoft::trigger(ServerEvent::BEFORE_BIND_EVENT);

        // register events
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

        \Swoft::trigger(ServerEvent::BEFORE_START);

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
     * @return string
     */
    public function getModeName(): string
    {
        return self::MODE_LIST[$this->mode] ?? 'Unknown';
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return self::MODE_LIST[$this->type] ?? 'Unknown';
    }

    /**
     * @return array
     */
    public function getSetting(): array
    {
        return $this->setting;
    }

    /**
     * @param array $setting
     */
    public function setSetting(array $setting): void
    {
        $this->setting = \array_merge($this->setting, $setting);
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

    /**
     * Restart server
     */
    public function restart(): void
    {
        // Check if it has started
        if ($this->isRunning()) {
            $this->stop();
        }

        // Restart default is daemon
        $this->setDaemonize();

        // Start server
        $this->start();
    }

    /**
     * @param bool $onlyTaskWorker
     * @return bool
     */
    public function reload(bool $onlyTaskWorker = false): bool
    {
        if (($pid = $this->pidMap['manager']) < 1) {
            return false;
        }

        // SIGUSR1(10): 向管理进程发送信号，将平稳地重启所有worker进程
        // SIGUSR2(12): 向管理进程发送信号，只重启task进程
        $signal = $onlyTaskWorker ? 12 : 10;

        return ServerHelper::sendSignal($pid, $signal);
    }

    public function stop(): bool
    {
        if (($pid = $this->pidMap['manager']) < 1) {
            return false;
        }

        // SIGTERM = 15
        if (ServerHelper::killAndWait($pid, 15, 'Swoft')) {
            return ServerHelper::removePidFile($this->pidFile);
        }

        return false;
    }

    public function shutdown(): void
    {
        $this->swooleServer->shutdown();
    }

    /**
     * Stop the worker process and immediately trigger the onWorkerStop callback function
     * @param int  $workerId
     * @param bool $waitEvent
     * @return bool
     */
    public function stopWorker(int $workerId = -1, bool $waitEvent = false): bool
    {
        if ($workerId > -1 && $this->swooleServer) {
            return $this->swooleServer->stop($workerId, $waitEvent);
        }

        return false;
    }

    /**
     * print log message to terminal
     * @param string $msg
     * @param array  $data
     * @param string $type
     */
    public function log(string $msg, array $data = [], string $type = 'info'): void
    {
        if ($this->isDaemonize()) {
            return;
        }

        if (\config('debug')) {
            // TODO ...
            // ConsoleUtil::log($msg, $data, $type);
        }
    }

    /**
     * Check if the server is running
     *
     * @return bool
     */
    public function isRunning(): bool
    {
        $pidFile = $this->pidFile;

        // Is pid file exist ?
        if (\file_exists($pidFile)) {
            // Get pid file content and parse the content
            $pidFile = \file_get_contents($pidFile);
            // parse and record PIDs
            [$masterPID, $managerPID] = \explode(',', $pidFile);
            $this->pidMap['master']  = (int)$masterPID;
            $this->pidMap['manager'] = (int)$managerPID;

            return $managerPID > 0 && Process::kill($managerPID, 0);
        }

        return false;
    }

    /**
     * @return array
     */
    public function getPidMap(): array
    {
        return $this->pidMap;
    }

    /**
     * @param string $name
     * @return int
     */
    public function getPid(string $name = 'master'): int
    {
        return $this->pidMap[$name] ?? 0;
    }

    /**
     * @return string
     */
    public function getPidFile(): string
    {
        return $this->pidFile;
    }

    /**
     * @return \Co\Http\Server|CoServer|\Co\Websocket\Server
     */
    public function getSwooleServer()
    {
        return $this->swooleServer;
    }

    /**
     * Set server, run server on the background
     *
     * @param bool $yes
     * @return $this
     */
    public function setDaemonize(bool $yes = true): self
    {
        $this->setting['daemonize'] = $yes ? 1 : 0;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDaemonize(): bool
    {
        return (int)$this->setting['daemonize'] === 1;
    }
}