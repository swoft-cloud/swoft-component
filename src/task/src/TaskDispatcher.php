<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Task;

use Swoft;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\BeanFactory;
use Swoft\Log\Helper\CLog;
use Swoft\Stdlib\Helper\PhpHelper;
use Swoft\Task\Exception\TaskException;
use Swoft\Task\Router\Router;
use Throwable;

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
     */
    public function dispatch(Request $request, Response $response): void
    {
        Swoft::trigger(TaskEvent::BEFORE_TASK, null, $request, $response);

        $result = null;
        try {
            $result = $this->handle($request);
            $response->setResult($result);
        } catch (Throwable $e) {
            CLog::error('task exec error: %s', $e->getMessage());
            $response->setErrorCode($e->getCode());
            $response->setErrorMessage($e->getMessage());
        }

        Swoft::trigger(TaskEvent::AFTER_TASK, null, $response);
    }

    /**
     * @param Request $request
     *
     * @return mixed
     * @throws TaskException
     */
    private function handle(Request $request)
    {
        $name   = $request->getName();
        $method = $request->getMethod();
        $params = $request->getParams();

        /* @var Router $router */
        $router = BeanFactory::getBean('taskRouter');

        $match = $router->match($name, $method);
        [$status, $handler] = $match;

        if ($status !== Router::FOUND || empty($handler)) {
            throw new TaskException(sprintf('Task(name=%s method=%s) is not exist!', $name, $method));
        }

        [$className, $methodName] = $handler;
        $object = BeanFactory::getBean($className);
        if (!method_exists($object, $methodName)) {
            throw new TaskException(sprintf('Task(name=%s method=%s) method is not exist!', $name, $method));
        }

        return PhpHelper::call([$object, $methodName], ...$params);
    }
}
