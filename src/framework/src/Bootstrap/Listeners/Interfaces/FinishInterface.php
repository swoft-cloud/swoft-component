<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Bootstrap\Listeners\Interfaces;

use Swoole\Server;

/**
 * FinishInterface
 */
interface FinishInterface
{
    /**
     * @param Server $server
     * @param int    $taskId
     * @param mixed  $data
     *
     * @return mixed
     */
    public function onFinish(Server $server, int $taskId, $data);
}
