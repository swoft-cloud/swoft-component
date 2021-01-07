<?php
/**
 * The file is part of the swoft_marketing_engine.
 *
 * (c) alan <alan1766447919@gmail.com>.
 *
 * 2021/1/3 1:43 下午
 */

namespace Swoft\Server\Context;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Concern\PrototypeTrait;
use Swoft\Context\AbstractContext;

/**
 * Class PipeMessageContext
 * @package App\Context
 *
 * @Bean(scope=Bean::PROTOTYPE)
 */
class PipeMessageContext extends AbstractContext
{
    use PrototypeTrait;

    /**
     * @param int $srcWorkerId
     * @param $pipeMessage
     * @return PipeMessageContext
     */
    public static function new(int $srcWorkerId, $pipeMessage): self
    {
        $self = self::__instance();

        $self->set('srcWorkerId', $srcWorkerId);
        $self->set('pipeMessage', $pipeMessage);

        return $self;
    }

    /**
     * @return int
     */
    public function getSrcWorkerId(): int
    {
        return $this->get('srcWorkerId');
    }

    /**
     * @return mixed
     */
    public function getPipeMessage()
    {
        return $this->get('pipeMessage');
    }
}

