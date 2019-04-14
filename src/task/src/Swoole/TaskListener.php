<?php declare(strict_types=1);


namespace Swoft\Task\Swoole;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\BeanFactory;
use Swoft\Server\Swoole\TaskInterface;
use Swoft\Task\Request;
use Swoft\Task\Response;
use Swoft\Task\TaskDispatcher;
use Swoole\Server;
use Swoole\Server\Task as SwooleTask;

/**
 * Class TaskListener
 *
 * @since 2.0
 *
 * @Bean()
 */
class TaskListener implements TaskInterface
{
    /**
     * @param Server     $server
     * @param SwooleTask $task
     *
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Task\Exception\TaskException
     */
    public function onTask(Server $server, SwooleTask $task)
    {
        $request  = Request::new($server, $task);
        $response = Response::new($task);

        /* @var TaskDispatcher $dispatcher */
        $dispatcher = BeanFactory::getBean('taskDispatcher');
        $dispatcher->dispatch($request, $response);
    }
}