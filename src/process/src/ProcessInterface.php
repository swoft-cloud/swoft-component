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

use Swoft\Process\Process as SwoftProcess;

/**
 * The process interface
 */
interface ProcessInterface
{
    /**
     * @param SwoftProcess $process
     */
    public function run(SwoftProcess $process);

    /**
     * @return bool
     */
    public function check(): bool;
}
