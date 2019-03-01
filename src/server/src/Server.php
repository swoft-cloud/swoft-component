<?php declare(strict_types=1);

namespace Swoft\Server;

use Swoft;
use Swoft\Console\Console;
use Swoft\Server\Event\ServerStartEvent;
use Swoft\Server\Event\WorkerEvent;
use Swoft\Server\Exception\ServerException;
use Swoft\Server\Helper\ServerHelper;
use Swoft\Server\Swoole\SwooleEvent;
use Swoft\Stdlib\Helper\Dir;
use Swoft\Stdlib\Helper\Sys;
use Swoole\Process;
use Swoole\Server as CoServer;

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
     * @var \Swoft\Server\Server|\Swoft\Http\Server\HttpServer|\Swoft\WebSocket\Server\WebSocketServer
     */
    private static $server;

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
    protected $port = 80;

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
        'daemonize'  => 0,
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
     * Record started server PIDs and with current workerId
     *
     * @var array
     */
    private $pidMap = [
        'masterPid'  => 0,
        'managerPid' => 0,
        // if = 0, current is at master/manager process.
        'workerPid'  => 0,
        // if < 0, current is at master/manager process.
        'workerId'   => -1,
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
     * @var bool
     */
    private $debug = false;

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
        // Do something ...
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
    public function onStart(CoServer $server): void
    {
        // Save PID to property
        $this->setPidMap($server);

        $masterPid  = $server->master_pid;
        $managerPid = $server->manager_pid;

        $pidStr = \sprintf('%s,%s', $masterPid, $managerPid);
        $title  = \sprintf('%s master process (%s)', $this->pidName, $this->scriptFile);

        // Save PID to file
        $pidFile = \alias($this->pidFile);
        Dir::make(\dirname($pidFile));
        \file_put_contents(\alias($this->pidFile), $pidStr);

        // Set process title
        Sys::setProcessTitle($title);

        // Update setting property
        $this->setSetting($server->setting);

        Swoft::trigger(new ServerStartEvent(SwooleEvent::START, $server));
    }

    /**
     * Manager start event
     *
     * @param CoServer $server
     *
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function onManagerStart(CoServer $server): void
    {
        // Server pid map
        $this->setPidMap($server);

        // Set process title
        Sys::setProcessTitle(\sprintf('%s manager process', $this->pidName));

        Swoft::trigger(new ServerStartEvent(SwooleEvent::MANAGER_START, $server));
    }

    /**
     * Manager stop event
     *
     * @param CoServer $server
     *
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function onManagerStop(CoServer $server): void
    {
        Swoft::trigger(new ServerStartEvent(SwooleEvent::MANAGER_STOP, $server));
    }

    /**
     * Worker start event
     *
     * @param CoServer $server
     * @param int      $workerId
     *
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function onWorkerStart(CoServer $server, int $workerId): void
    {
        $isTaskProcess = $workerId >= $server->setting['worker_num'];

        $event = new WorkerEvent(SwooleEvent::WORKER_START, $server, $workerId);
        // is task process
        $event->taskProcess = $isTaskProcess;

        Swoft::trigger($event);

        // Save PID and workerId
        $this->pidMap['workerId']  = $workerId;
        $this->pidMap['workerPid'] = $server->worker_pid;

        // Task process
        if ($isTaskProcess) {
            $newEvent = clone $event;
            $newEvent->setName(ServerEvent::TASK_PROCESS_START);

            Sys::setProcessTitle(sprintf('%s task process', $this->pidName));
            Swoft::trigger($newEvent);

            // Worker process
        } else {
            $newEvent = clone $event;
            $newEvent->setName(ServerEvent::WORK_PROCESS_START);

            Sys::setProcessTitle(sprintf('%s worker process', $this->pidName));
            Swoft::trigger($newEvent);
        }
    }

    /**
     * Worker stop event
     *
     * @param CoServer $server
     * @param int      $workerId
     *
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function onWorkerStop(CoServer $server, int $workerId): void
    {
        $event = new WorkerEvent(SwooleEvent::WORKER_STOP, $server, $workerId);
        // is task process
        $event->taskProcess = $workerId >= $server->setting['worker_num'];

        Swoft::trigger($event);
    }

    /**
     * Worker error stop event
     *
     * @param CoServer $server
     * @param int      $workerId
     * @param int      $workerPid
     * @param int      $exitCode
     * @param int      $signal
     *
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function onWorkerError(CoServer $server, int $workerId, int $workerPid, int $exitCode, int $signal): void
    {
        $event = new WorkerEvent(SwooleEvent::WORKER_ERROR, $server, $workerId);
        // is task process
        $event->taskProcess = $workerId >= $server->setting['worker_num'];
        $event->setParams([
            'signal'    => $signal,
            'exitCode'  => $exitCode,
            'workerPid' => $workerPid,
        ]);

        Swoft::trigger($event);
    }

    /**
     * Shutdown event
     *
     * @param CoServer $server
     *
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function onShutdown(CoServer $server): void
    {
        Swoft::trigger(new ServerStartEvent(SwooleEvent::SHUTDOWN, $server));
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

        Swoft::trigger(ServerEvent::BEFORE_SETTING);

        // Set settings
        $this->swooleServer->set($this->setting);

        Swoft::trigger(ServerEvent::BEFORE_BIND_EVENT);

        // Register events
        $defaultEvents = $this->defaultEvents();
        $swooleEvents  = \array_merge($defaultEvents, $this->on);

        foreach ($swooleEvents as $name => $listener) {
            // Default events
            if (isset($defaultEvents[$name])) {
                $this->swooleServer->on($name, $listener);
                continue;
            }

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

        Swoft::trigger(ServerEvent::BEFORE_START);

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
        return self::TYPE_LIST[$this->type] ?? 'Unknown';
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
     * @return \Swoft\Server\Server|\Swoft\Http\Server\HttpServer|\Swoft\WebSocket\Server\WebSocketServer
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
        // Restart default is daemon
        $this->setDaemonize();

        // Start server
        $this->start();
    }

    /**
     * @param bool $onlyTaskWorker
     *
     * @return bool
     */
    public function reload(bool $onlyTaskWorker = false): bool
    {
        if (($pid = $this->pidMap['managerPid']) < 1) {
            return false;
        }

        // SIGUSR1(10): 向管理进程发送信号，将平稳地重启所有worker进程
        // SIGUSR2(12): 向管理进程发送信号，只重启task进程
        $signal = $onlyTaskWorker ? 12 : 10;

        return ServerHelper::sendSignal($pid, $signal);
    }

    /**
     * @return bool
     */
    public function stop(): bool
    {
        $pid = $this->getPid();
        if ($pid < 1) {
            return false;
        }

        // SIGTERM = 15
        if (ServerHelper::killAndWait($pid, 15, $this->pidName)) {
            return ServerHelper::removePidFile(\alias($this->pidFile));
        }

        return false;
    }

    /**
     * Shutdown server
     */
    public function shutdown(): void
    {
        $this->swooleServer->shutdown();
    }

    /**
     * Stop the worker process and immediately trigger the onWorkerStop callback function
     *
     * @param int  $workerId
     * @param bool $waitEvent
     *
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
     * Print log message to terminal
     *
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
            Console::log($msg, $data, $type);
        }
    }

    /**
     * Check if the server is running
     *
     * @return bool
     */
    public function isRunning(): bool
    {
        $pidFile = \alias($this->pidFile);

        // Is pid file exist ?
        if (\file_exists($pidFile)) {
            // Get pid file content and parse the content
            $pidFile = \file_get_contents($pidFile);

            // Parse and record PIDs
            [$masterPID, $managerPID] = \explode(',', $pidFile);
            $managerPID = (int)$masterPID;

            $this->pidMap['masterPid']  = $masterPID;
            $this->pidMap['managerPid'] = (int)$managerPID;

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
     *
     * @return int
     */
    public function getPid(string $name = 'masterPid'): int
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
     * @return bool
     */
    public function isDebug(): bool
    {
        return $this->debug;
    }

    /**
     * @param bool $debug
     */
    public function setDebug($debug): void
    {
        $this->debug = (bool)$debug;
    }

    /**
     * Set server, run server on the background
     *
     * @param bool $yes
     *
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

    /**
     * @return int
     */
    public function getErrorNo(): int
    {
        return $this->swooleServer->getLastError();
    }

    /**
     * @param int $fd
     * @return array
     */
    public function getClientInfo(int $fd): array
    {
        return $this->swooleServer->getClientInfo($fd);
    }

    /**
     * Set pid map
     *
     * @param CoServer $server
     */
    protected function setPidMap(CoServer $server): void
    {
        if ($server->master_pid > 0) {
            $this->pidMap['masterPid'] = $server->master_pid;
        }

        if ($server->manager_pid > 0) {
            $this->pidMap['managerPid'] = $server->manager_pid;
        }
    }

    /**
     * @return array
     */
    public function defaultEvents(): array
    {
        return [
            SwooleEvent::WORKER_ERROR  => [$this, 'onWorkerError'],
            SwooleEvent::WORKER_STOP   => [$this, 'onWorkerStop'],
            SwooleEvent::MANAGER_STOP  => [$this, 'onManagerStop'],
            SwooleEvent::MANAGER_START => [$this, 'onManagerStart'],
            SwooleEvent::WORKER_START  => [$this, 'onWorkerStart'],
            SwooleEvent::SHUTDOWN      => [$this, 'onShutdown'],
            SwooleEvent::START         => [$this, 'onStart'],
        ];
    }
}
