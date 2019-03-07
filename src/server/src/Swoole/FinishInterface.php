<?php declare(strict_types=1);

namespace Swoft\Server\Swoole;

use Swoole\Server;

/**
 * Interface FinishInterface
 *
 * @since 2.0
 */
interface FinishInterface
{
    /**
     * Finish event
     *
     * @param Server $server
     * @param int      $taskId
     * @param string   $data
     */
    public function onFinish(Server $server, int $taskId, string $data): void;
}