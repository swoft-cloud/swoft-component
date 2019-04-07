<?php declare(strict_types=1);


namespace Swoft\Task;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\BeanFactory;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Stdlib\Helper\PhpHelper;
use Swoft\Task\Exception\TaskException;
use Swoft\Task\Router\Router;

/**
 * Class TaskDispatcher
 *
 * @since 2.0
 * @Bean("taskDispatcher")
 */
class TaskDispatcher
{
    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return mixed
     * @throws ContainerException
     */
    public function dispatch(Request $request, Response $response)
    {
        \Swoft::trigger(TaskEvent::BEFORE_TASK);

        $result = null;
        try {
            $result = $this->handle($request);
            $response->setResult($result);
        } catch (\Throwable $e) {
            $response->setErrorCode($e->getCode());
            $response->setErrorMessage($e->getMessage());
        }

        \Swoft::trigger(TaskEvent::AFTER_TASK);

        if ($request->getType() == Task::CO) {
            return Packet::packResponse($result);
        }

        return null;
    }

    /**
     * @param Request $request
     *
     * @return mixed
     * @throws TaskException
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    private function handle(Request $request)
    {
        $name   = $request->getName();
        $method = $request->getMethod();
        $params = $request->getParams();

        /* @var Router $router */
        $router = BeanFactory::getBean('taskRouter');

        $match = $router->match($name, $method);
        [$status, $className] = $match;

        if ($status != Router::FOUND) {
            throw new TaskException(
                sprintf('Task(name=%s method=%s) is not exist!', $name, $method)
            );
        }

        $object = BeanFactory::getBean($className);
        if (!method_exists($object, $method)) {
            throw new TaskException(
                sprintf('Task(name=%s method=%s) is not exist!', $name, $method)
            );
        }

        return PhpHelper::call([$object, $method], ... $params);
    }
}