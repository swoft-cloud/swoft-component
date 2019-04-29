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
use Swoft\Stdlib\Helper\Str;
use Swoft\Stdlib\Helper\Sys;
use Swoole\Process;
use Swoole\Server as CoServer;
use Swoft\Stdlib\Helper\Arr;

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
     * @link https://wiki.swoole.com/wiki/page/274.html
     * @var array
     */
    protected $setting = [];

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
     * Add port listener
     *
     * @var array
     *
     * @example
     * [
     *    'name' => ServerInterface,
     *    'name2' => ServerInterface,
     * ]
     */
    protected $listener = [];

    /**
     * Script file
     *
     * @var string
     */
    protected $scriptFile = '';

    /**
     * Swoole Server
     *
     * @var \Swoole\Server|\Swoole\Http\Server|\Swoole\Websocket\Server
     */
    protected $swooleServer;

    /**
     * Debug level
     *
     * @var integer
     */
    private $debug = 0;

    /**
     * Server id
     *
     * @var string
     */
    private $id = '';

    /**
     * Server unique id
     *
     * @var string
     */
    private $uniqid = '';

    /**
     * Server constructor
     */
    public function __construct()
    {
        // Init default settings
        $this->setting = $this->defaultSetting();

        // Init
        $this->init();
    }

    /**
     * Init
     */
    public function init(): void
    {
        $this->uniqid = Str::uniqID('', true);
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
     * @throws \Throwable
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

        Swoft::trigger(new ServerStartEvent(SwooleEvent::START, $server), $this);
    }

    /**
     * Manager start event
     *
     * @param CoServer $server
     *
     * @throws \Throwable
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
     * @throws \Throwable
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
     * @throws \Throwable
     */
    public function onWorkerStart(CoServer $server, int $workerId): void
    {
        // Save PID and workerId
        $this->pidMap['workerId']  = $workerId;
        $this->pidMap['workerPid'] = $server->worker_pid;

        $event = new WorkerEvent(SwooleEvent::WORKER_START, $server, $workerId);
        // Is task process
        $isTaskProcess      = $workerId >= $server->setting['worker_num'];
        $event->taskProcess = $isTaskProcess;

        Swoft::trigger($event);

        // Task process
        if ($isTaskProcess) {
            $procRole  = 'task';
            $eventName = ServerEvent::TASK_PROCESS_START;
            // Worker process
        } else {
            $procRole  = 'worker';
            $eventName = ServerEvent::WORK_PROCESS_START;
        }

        // For special role process
        $newEvent = clone $event;
        $newEvent->setName($eventName);

        Sys::setProcessTitle(\sprintf('%s %s process', $this->pidName, $procRole));
        Swoft::trigger($newEvent);
    }

    /**
     * Worker stop event
     *
     * @param CoServer $server
     * @param int      $workerId
     *
     * @throws \Throwable
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
     * @throws \Throwable
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
     * @throws \Throwable
     */
    public function onShutdown(CoServer $server): void
    {
        Swoft::trigger(new ServerStartEvent(SwooleEvent::SHUTDOWN, $server));
    }

    /**
     * Bind swoole event and start swoole server
     *
     * @throws ServerException
     * @throws Swoft\Bean\Exception\ContainerException
     */
    protected function startSwoole(): void
    {
        if (!$this->swooleServer) {
            throw new ServerException('You must to new server before start swoole!');
        }

        Swoft::trigger(ServerEvent::BEFORE_SETTING, $this);

        // Set settings
        $this->swooleServer->set($this->setting);

        Swoft::trigger(ServerEvent::BEFORE_BIND_EVENT, $this);

        // Register events
        $defaultEvents = $this->defaultEvents();
        $swooleEvents  = \array_merge($defaultEvents, $this->on);

        // Add event
        $this->addEvent($this->swooleServer, $swooleEvents, $defaultEvents);

        // Add port listener
        $this->addListener();

        // @todo

        // Trigger
        Swoft::trigger(ServerEvent::BEFORE_START, $this, array_keys($swooleEvents));

        // Storage server instance
        self::$server = $this;

        // Start swoole server
        $this->swooleServer->start();
    }

    /**
     * Add listener
     *
     * @throws ServerException
     */
    protected function addListener(): void
    {
        foreach ($this->listener as $listener) {
            if (!$listener instanceof ServerInterface) {
                continue;
            }

            $host = $listener->getHost();
            $port = $listener->getPort();
            $type = $listener->getType();
            $on   = $listener->getOn();

            /* @var CoServer\Port $server */
            $server = $this->swooleServer->listen($host, $port, $type);
            $server->set([
                'open_eof_check'     => false,
                'package_max_length' => 2048
            ]);
            $this->addEvent($server, $on);
        }
    }

    /**
     * Add events
     *
     * @param \Swoole\Server|CoServer\Port $server
     * @param array                        $swooleEvents
     * @param array                        $defaultEvents
     *
     * @throws ServerException
     */
    protected function addEvent($server, array $swooleEvents, array $defaultEvents = []): void
    {
        foreach ($swooleEvents as $name => $listener) {
            // Default events
            if (isset($defaultEvents[$name])) {
                $server->on($name, $listener);
                continue;
            }

            if (!isset(SwooleEvent::LISTENER_MAPPING[$name])) {
                throw new ServerException(\sprintf('Swoole %s event is not defined!', $name));
            }

            $listenerInterface = SwooleEvent::LISTENER_MAPPING[$name];
            if (!($listener instanceof $listenerInterface)) {
                throw new ServerException(\sprintf('Swoole %s event listener is not %s', $name, $listenerInterface));
            }

            $listenerMethod = \sprintf('on%s', \ucfirst($name));
            $server->on($name, [$listener, $listenerMethod]);
        }
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @param string $host
     */
    public function setHost(string $host): void
    {
        $this->host = $host;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @param int $port
     */
    public function setPort(int $port): void
    {
        $this->port = $port;
    }

    /**
     * @return int
     */
    public function getMode(): int
    {
        return $this->mode;
    }

    /**
     * @param int $mode
     */
    public function setMode(int $mode): void
    {
        if (!isset(self::MODE_LIST[$mode])) {
            throw new \InvalidArgumentException('invalid server mode value: ' . $mode);
        }

        $this->mode = $mode;
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
     * @param int $type
     */
    public function setType(int $type): void
    {
        if (!isset(self::TYPE_LIST[$type])) {
            throw new \InvalidArgumentException('invalid server type value: ' . $type);
        }

        $this->type = $type;
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
     * @param string $eventName
     *
     * @return bool
     */
    public function hasListener(string $eventName): bool
    {
        return isset($this->on[$eventName]);
    }

    /**
     * @return array
     */
    public function getRegisteredEvents(): array
    {
        return \array_keys($this->on);
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
    public static function getServer(): ?Server
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
        if (($pid = $this->pidMap['masterPid']) < 1) {
            return false;
        }

        // SIGUSR1(10):
        //  Send a signal to the management process that will smoothly restart all worker processes
        // SIGUSR2(12):
        //  Send a signal to the management process, only restart the task process
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
     * Response data to client by socket connection
     *
     * @param int    $fd
     * @param string $data
     * param int $length
     *
     * @return bool
     */
    public function writeTo(int $fd, string $data): bool
    {
        return $this->swooleServer->send($fd, $data);
    }

    /**
     * @param int $fd
     *
     * @return bool
     */
    public function exist(int $fd): bool
    {
        return $this->swooleServer->exist($fd);
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

        if ($this->debug > 0) {
            $tid = Swoft\Co::tid();
            $cid = Swoft\Co::id();
            $wid = $this->getPid('workerId');

            Console::log("[WorkerId:$wid, TID:$tid, CID:$cid] " . $msg, $data, $type);
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
            // Format type
            $masterPID  = (int)$masterPID;
            $managerPID = (int)$managerPID;

            $this->pidMap['masterPid']  = $masterPID;
            $this->pidMap['managerPid'] = $managerPID;

            return $masterPID > 0 && Process::kill($masterPID, 0);
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
     * @return \Swoole\Http\Server|CoServer|\Swoole\Websocket\Server
     */
    public function getSwooleServer()
    {
        return $this->swooleServer;
    }

    /**
     * @return array
     */
    public function getSwooleStats(): array
    {
        return $this->swooleServer->stats();
    }

    /**
     * @return bool
     */
    public function isDebug(): bool
    {
        return $this->debug > 0;
    }

    /**
     * @param int $debug
     */
    public function setDebug($debug): void
    {
        $this->debug = (int)$debug;
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
     *
     * @return array
     */
    public function getClientInfo(int $fd): array
    {
        return (array)$this->swooleServer->getClientInfo($fd);
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
    public function getListener(): array
    {
        return $this->listener;
    }

    /**
     * @return array
     */
    public function defaultEvents(): array
    {
        return [
            SwooleEvent::START         => [$this, 'onStart'],
            SwooleEvent::SHUTDOWN      => [$this, 'onShutdown'],
            SwooleEvent::MANAGER_START => [$this, 'onManagerStart'],
            SwooleEvent::MANAGER_STOP  => [$this, 'onManagerStop'],
            SwooleEvent::WORKER_START  => [$this, 'onWorkerStart'],
            SwooleEvent::WORKER_STOP   => [$this, 'onWorkerStop'],
            SwooleEvent::WORKER_ERROR  => [$this, 'onWorkerError'],
        ];
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUniqid(): string
    {
        return $this->uniqid;
    }

    /**
     * @return array
     */
    protected function defaultSetting(): array
    {
        return [
            'daemonize'       => 0,
            'worker_num'      => 1,

            // If > 0, must listen event: task, finish
            'task_worker_num' => 0
        ];
    }
}
