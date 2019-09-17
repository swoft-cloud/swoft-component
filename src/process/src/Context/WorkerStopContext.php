<?php declare(strict_types=1);


namespace Swoft\Process\Context;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Concern\PrototypeTrait;
use Swoft\Context\AbstractContext;
use Swoole\Process\Pool;

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
     * @var Pool
     */
    private $pool;

    /**
     * @var int
     */
    private $workerId;

    /**
     * @param Pool $pool
     * @param int  $workerId
     *
     * @return WorkerStopContext
     */
    public static function new(Pool $pool, int $workerId): self
    {
        $self = self::__instance();

        $self->pool     = $pool;
        $self->workerId = $workerId;

        return $self;
    }
}
