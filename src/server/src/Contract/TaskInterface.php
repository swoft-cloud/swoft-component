<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Server\Contract;

use Swoft\Task\Exception\TaskException;
use Swoole\Server;
use Swoole\Server\Task as SwooleTask;

/**
 * Class TaskInterface
 *
 * @since 2.0
 */
interface TaskInterface
{
    /**
     * @param Server     $server
     * @param SwooleTask $task
     *
     * @throws TaskException
     */
    public function onTask(Server $server, SwooleTask $task): void;
}
