<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Server\Context;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Concern\PrototypeTrait;
use Swoft\Context\AbstractContext;
use Swoole\Server as SwooleServer;

/**
 * Class WorkerStopContext
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::PROTOTYPE)
 */
class WorkerStopContext extends AbstractContext
{
    use PrototypeTrait;

    /**
     * @var SwooleServer
     */
    private $server;

    /**
     * @var int
     */
    private $workerId;

    /**
     * @param SwooleServer $server
     * @param int          $workerId
     *
     * @return WorkerStopContext
     */
    public static function new(SwooleServer $server, int $workerId): self
    {
        $self = self::__instance();

        $self->server   = $server;
        $self->workerId = $workerId;

        return $self;
    }

    /**
     * @return SwooleServer
     */
    public function getSwooleServer(): SwooleServer
    {
        return $this->server;
    }

    /**
     * @return int
     */
    public function getWorkerId(): int
    {
        return $this->workerId;
    }
}
