<?php declare(strict_types=1);

namespace Swoft\Console;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Co;
use Swoft\Console\Input\Input;
use Swoft\Console\Output\Output;
use Swoft\Contract\DispatcherInterface;
use Swoft\Stdlib\Helper\PhpHelper;
use Swoole\Event;

/**
 * Class ConsoleDispatcher
 * @since 2.0
 * @Bean("cliDispatcher")
 */
class ConsoleDispatcher implements DispatcherInterface
{
    /**
     * @param array $params
     * @return void
     * @throws \ReflectionException
     */
    public function dispatch(...$params): void
    {
        $route = $params[0];
        // Handler info
        [$className, $method] = $route['handler'];

        // Bind method params
        $bindParams = $this->getBindParams($className, $method);
        $beanObject = \Swoft::getSingleton($className);

        // Blocking running
        if (!$route['coroutine']) {
            $this->before(\get_parent_class($beanObject), $method);
            PhpHelper::call([$beanObject, $method], ...$bindParams);
            $this->after($method);
            return;
        }

        // Coroutine running
        Co::create(function () use ($beanObject, $method, $bindParams) {
            $this->executeByCo($beanObject, $method, $bindParams);
        });

        Event::wait();
    }

    /**
     * @param object $beanObject
     * @param string $method
     * @param array  $bindParams
     */
    public function executeByCo($beanObject, string $method, array $bindParams): void
    {
        $this->before($method, \get_class($beanObject));

        PhpHelper::call([$beanObject, $method], ...$bindParams);

        $this->after($method);
    }

    /**
     * Get method bounded params
     *
     * @param string $class
     * @param string $method
     * @return array
     * @throws \ReflectionException
     */
    private function getBindParams(string $class, string $method): array
    {
        $classInfo = \Swoft::getReflection($class);
        if (!isset($classInfo['methods'][$method])) {
            return [];
        }

        // binding params
        $bindParams   = [];
        $methodParams = $classInfo['methods'][$method]['params'];

        /**
         * @var string               $key
         * @var \ReflectionParameter $reflectParam
         */
        foreach ($methodParams as $key => $reflectParam) {
            $reflectType = $reflectParam->getType();

            // undefined type of the param
            if ($reflectType === null) {
                $bindParams[$key] = null;
                continue;
            }

            // defined type of the param
            $type = $reflectType->getName();
            if ($type === Output::class) {
                $bindParams[$key] = \output();
            } elseif ($type === Input::class) {
                $bindParams[$key] = \input();
            } else {
                $bindParams[$key] = null;
            }
        }

        return $bindParams;
    }

    /**
     * Pre middleware
     *
     * @return array
     */
    public function preMiddleware(): array
    {
        return [];
    }

    /**
     * After middleware
     *
     * @return array
     */
    public function afterMiddleware(): array
    {
        return [];
    }

    /**
     * Before dispatch
     *
     * @param array $params
     */
    public function before(...$params): void
    {
        // TODO ... event params
        $command = $params[0];
        \Swoft::trigger(ConsoleEvent::BEFORE_EXECUTE, $command, $params[1]);
    }

    /**
     * After dispatch
     *
     * @param array $params
     */
    public function after(...$params): void
    {
        // TODO ... event params
        $command = $params[0];

        \Swoft::triggerByArray(ConsoleEvent::AFTER_EXECUTE, $command, [
            'command' => $command,
        ]);
    }
}
