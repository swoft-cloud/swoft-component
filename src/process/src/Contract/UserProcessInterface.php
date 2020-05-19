<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Process\Contract;

use Swoft\Process\Process;

/**
 * Class UserProcessInterface
 *
 * @since 2.0
 */
interface UserProcessInterface
{
    /**
     * Run
     *
     * @param Process $process
     */
    public function run(Process $process): void;

    /**
     * @return bool
     */
    public function isStdinOut(): bool;

    /**
     * @return int
     */
    public function getPipeType(): int;

    /**
     * @return bool
     */
    public function isCoroutine(): bool;

    /**
     * @param \Swoole\Process $process
     */
    public function setSwooleProcess(\Swoole\Process $process): void;

    /**
     * @return \Swoole\Process $process
     */
    public function getSwooleProcess(): \Swoole\Process;
}
