<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Process\Context;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Concern\PrototypeTrait;
use Swoft\Context\AbstractContext;
use Swoole\Process\Pool;

/**
 * Class ProcessContext
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::PROTOTYPE)
 */
class ProcessContext extends AbstractContext
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
     * @return ProcessContext
     */
    public static function new(Pool $pool, int $workerId): self
    {
        $self = self::__instance();

        $self->pool     = $pool;
        $self->workerId = $workerId;

        return $self;
    }

    /**
     * @return Pool
     */
    public function getPool(): Pool
    {
        return $this->pool;
    }

    /**
     * @return int
     */
    public function getWorkerId(): int
    {
        return $this->workerId;
    }
}
