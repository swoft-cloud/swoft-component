<?php declare(strict_types=1);


namespace Swoft\Task\Swoole;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Server\Swoole\FinishInterface;
use Swoole\Server;

/**
 * Class FinishListener
 *
 * @since 2.0
 *
 * @Bean()
 */
class FinishListener implements FinishInterface
{
    /**
     * @param Server $server
     * @param int    $taskId
     * @param string $data
     */
    public function onFinish(Server $server, int $taskId, string $data): void
    {
        var_dump('finish', $data);
    }
}