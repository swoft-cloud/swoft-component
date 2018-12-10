<?php
declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Process;

use Swoft\Helper\PhpHelper;
use Swoole\Process as SwooleProcess;

/**
 * Class Process
 * @package Swoft\Process
 */
class Process
{
    /**
     * @var SwooleProcess
     */
    private $process;

    /**
     * Process constructor.
     *
     * @param SwooleProcess  $process
     */
    public function __construct(SwooleProcess $process)
    {
        $this->process = $process;
    }

    /**
     * Start process
     *
     * @return mixed Create a successful PID to return to the child process, create a failure to return to false
     */
    public function start()
    {
        return $this->process->start();
    }

    /**
     * Set the name of process
     *
     * @param string $name
     */
    public function name(string $name)
    {
        if (!PhpHelper::isMac()) {
            $this->process->name($name);
        }
    }

    /**
     * Execute an external program
     *
     * @param string $execFile
     * @param array  $args
     *
     * @return bool
     */
    public function exec(string $execFile, array $args): bool
    {
        return $this->process->exec($execFile, $args);
    }

    /**
     * Write data into pipe
     *
     * @param string $data
     *
     * @return int
     */
    public function write(string $data): int
    {
        return $this->process->write($data);
    }

    /**
     * Read data from pipe
     *
     * @param int $bufferSize
     *
     * @return bool|string
     */
    public function read(int $bufferSize = 8192)
    {
        return $this->process->read($bufferSize);
    }

    /**
     * Sett the timeout of the pipe read or write
     *
     * @param float $timeout
     */
    public function setTimeout(double $timeout)
    {
        $this->process->setTimeout($timeout);
    }

    /**
     * Enable message queuing as internal process communication
     *
     * @param int $msgKey
     * @param int $mode
     */
    public function useQueue(int $msgKey = 0, int $mode = 2)
    {
        $this->process->useQueue($msgKey, $mode);
    }

    /**
     * Show the message queue status
     *
     * @return array
     */
    public function statQueue(): array
    {
        return $this->process->statQueue();
    }

    /**
     * Remove message queue
     */
    public function freeQueue()
    {
        $this->process->freeQueue();
    }

    /**
     * Send data to the message queue
     *
     * @param string $data
     *
     * @return bool
     */
    public function push(string $data): bool
    {
        return $this->process->push($data);
    }

    /**
     * Get data from message queue
     *
     * @param int $maxSize
     *
     * @return string
     */
    public function pop(int $maxSize = 8192): string
    {
        return $this->process->pop($maxSize);
    }

    /**
     * Close the pipe
     *
     * @param int $which
     *
     * @return bool
     */
    public function close(int $which = 0): bool
    {
        return $this->process->close($which);
    }

    /**
     * Exit child process
     *
     * @param int $status
     *
     * @return int
     */
    public function exit(int $status = 0): int
    {
        return $this->process->exit($status);
    }

    /**
     * @param int $pid
     * @param int $sigNo
     *
     * @return bool
     */
    public static function kill(int $pid, int $sigNo = SIGTERM): bool
    {
        return SwooleProcess::kill($pid, $sigNo);
    }

    /**
     * @param bool $blocking
     *
     * @return array|bool
     */
    public static function wait(bool $blocking = true)
    {
        return SwooleProcess::wait($blocking);
    }

    /**
     * @param bool $noChdir
     * @param bool $noClose
     *
     */
    public static function daemon(bool $noChdir = false, bool $noClose = false)
    {
        SwooleProcess::daemon($noChdir, $noClose);
    }

    /**
     * @param int      $sigNo
     * @param callable $callback
     */
    public static function signal(int $sigNo, callable $callback)
    {
        SwooleProcess::signal($sigNo, $callback);
    }

    /**
     * @param int $intervalUsec
     * @param int $type
     *
     * @return bool
     */
    public static function alarm(int $intervalUsec, int $type = 0): bool
    {
        return SwooleProcess::alarm($intervalUsec, $type);
    }

    /**
     * @param array $cpuSet
     *
     * @return bool
     */
    public static function setAffinity(array $cpuSet): bool
    {
        return SwooleProcess::setaffinity($cpuSet);
    }

    /**
     * @return \Swoole\Process
     */
    public function getProcess(): SwooleProcess
    {
        return $this->process;
    }
}
