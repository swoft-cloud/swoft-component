<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Process;

use Swoft\Process\Contract\UserProcessInterface;

/**
 * Class UserProcess
 *
 * @since 2.0
 */
abstract class UserProcess implements UserProcessInterface
{
    /**
     * @var bool
     */
    protected $stdinOut = false;

    /**
     * @var int
     */
    protected $pipeType = 2;

    /**
     * @var bool
     */
    protected $coroutine = true;

    /**
     * @var \Swoole\Process
     */
    protected $swooleProcess;

    /**
     * @return bool
     */
    public function isStdinOut(): bool
    {
        return $this->stdinOut;
    }

    /**
     * @return int
     */
    public function getPipeType(): int
    {
        return $this->pipeType;
    }

    /**
     * @return bool
     */
    public function isCoroutine(): bool
    {
        return $this->coroutine;
    }

    /**
     * @inheritDoc
     */
    public function setSwooleProcess(\Swoole\Process $process): void
    {
        $this->swooleProcess = $process;
    }

    /**
     * @inheritDoc
     */
    public function getSwooleProcess(): \Swoole\Process
    {
        return $this->swooleProcess;
    }
}
