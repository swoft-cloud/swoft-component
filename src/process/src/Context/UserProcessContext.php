<?php declare(strict_types=1);


namespace Swoft\Process\Context;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Concern\PrototypeTrait;
use Swoft\Context\AbstractContext;
use Swoft\Process\Process;
use Swoft\Server\Server;

/**
 * Class UserProcessContext
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::PROTOTYPE)
 */
class UserProcessContext extends AbstractContext
{
    use PrototypeTrait;

    /**
     * @var Process
     */
    private $process;

    /**
     * @var Server
     */
    private $server;

    /**
     * @param Server  $server
     * @param Process $process
     *
     * @return UserProcessContext
     */
    public static function new(Server $server, Process $process): self
    {
        $self = self::__instance();

        $self->server  = $server;
        $self->process = $process;

        return $self;
    }

    /**
     * @return Process
     */
    public function getProcess(): Process
    {
        return $this->process;
    }

    /**
     * @return Server
     */
    public function getServer(): Server
    {
        return $this->server;
    }
}
