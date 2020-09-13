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

use Swoole\Process\Pool;

/**
 * Class MessageInterface
 *
 * @since 2.0
 */
interface MessageInterface
{
    /**
     * @param Pool   $pool
     * @param string $data
     */
    public function onMessage(Pool $pool, string $data): void;
}
