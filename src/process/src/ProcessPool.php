<?php

namespace Swoft\Process;

use Swoft\App;
use Swoft\Bean\BeanFactory;
use Swoft\Bootstrap\Server\AbstractServer;
use Swoft\Bootstrap\SwooleEvent;
use Swoft\Core\InitApplicationContext;
use Swoft\Core\RequestContext;
use Swoft\Log\Log;
use Swoft\Process\Event\ProcessEvent;
use Swoole\Coroutine;
use Swoole\Process\Pool;
use Swoole\Server;

/**
 * The Process Pool
 */
class ProcessPool extends AbstractServer
{
    /**
     * @var array
     */
    private $processSetting = [];

    /**
     * @param array $settings
     */
    public function initSettings(array $settings)
    {
        parent::initSettings($settings);

        $this->processSetting = array_merge($this->processSetting, $settings['process']);
    }

    /**
     * Start process pool
     */
    public function start()
    {
        $type = $this->processSetting['ipc_type'];
        $pool = new \Swoole\Process\Pool($this->processSetting['worker_num'], $type, $this->processSetting['message_queue_key']);
        $pool->on(SwooleEvent::ON_WORKER_START, [$this, 'onProcessWorkerStart']);
        $pool->on(SwooleEvent::ON_WORKER_STOP, [$this, 'onProcessWorkStop']);

        if ($type == SWOOLE_IPC_SOCKET) {
            $host = $this->processSetting['host'];
            $port = $this->processSetting['port'];
            $pool->on(SwooleEvent::ON_MESSAGE, [$this, 'onProcessMessage']);
            $pool->listen($host, $port);
        }

        $this->server = $pool;

        $pool->start();
    }

    /**
     * Worker start
     *
     * @param Pool $pool
     * @param int  $workerId
     *
     * @throws \ReflectionException
     */
    public function onProcessWorkerStart(Pool $pool, int $workerId)
    {
        BeanFactory::reload();
        $initApplicationContext = new InitApplicationContext();
        $initApplicationContext->init();

        // Create Coroutine
        go(function () use ($pool, $workerId) {
            // Init
            App::trigger(ProcessEvent::BEFORE_PROCESS, null, 'process name');

            // Do worker
            App::trigger(ProcessEvent::WORKER_START, null, $pool, $workerId);

            // Release resources
            App::trigger(ProcessEvent::AFTER_PROCESS);
        });
    }

    /**
     * Work stop
     *
     * @param Pool $pool
     * @param int  $workerId
     */
    public function onProcessWorkStop(Pool $pool, int $workerId)
    {
        App::trigger(ProcessEvent::WORKER_STOP, null, $pool, $workerId);
    }

    /**
     * Do message
     *
     * @param Pool   $pool
     * @param string $message
     */
    public function onProcessMessage(Pool $pool, string $message)
    {
        App::trigger(ProcessEvent::MESSAGE, $pool, $message);
    }

    /**
     * @return bool
     */
    public function isRunning(): bool
    {
        return false;
    }
}