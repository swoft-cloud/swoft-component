<?php declare(strict_types=1);


namespace Swoft\Task;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Concern\PrototypeTrait;
use Swoft\Context\AbstractContext;
use Swoole\Server;
use Swoft\Bean\Exception\ContainerException;

/**
 * Class TaskContext
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::PROTOTYPE)
 */
class TaskContext extends AbstractContext
{
    public function clear(): void
    {
        // TODO: Implement clear() method.
    }

}