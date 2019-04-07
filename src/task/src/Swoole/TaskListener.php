<?php declare(strict_types=1);


namespace Swoft\Task\Swoole;


use Swoft\Bean\BeanFactory;
use Swoft\Server\Swoole\TaskInterface;
use Swoft\Task\Request;
use Swoft\Task\Response;
use Swoft\Task\TaskDispatcher;
use Swoole\Server;

/**
 * Class TaskListener
 *
 * @since 2.0
 */
class TaskListener implements TaskInterface
{
    /**
     * @param Server $server
     * @param int    $taskId
     * @param int    $srcWorkerId
     * @param mixed  $data
     *
     * @return mixed
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Task\Exception\TaskException
     */
    public function onTask(Server $server, int $taskId, int $srcWorkerId, $data)
    {
        $request  = Request::new($server, $taskId, $srcWorkerId, $data);
        $response = Response::new(null, null, '');

        /* @var TaskDispatcher $dispatcher */
        $dispatcher = BeanFactory::getBean('taskDispatcher');
        $result     = $dispatcher->dispatch($request, $response);

        return $result;
    }
}