<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Console;

use ReflectionException;
use ReflectionType;
use Swoft;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Console\Input\Input;
use Swoft\Console\Output\Output;
use Swoft\Context\Context;
use Swoft\SwoftEvent;
use Swoole\Runtime;
use Throwable;
use function defined;
use function get_class;
use function srun;

/**
 * Class ConsoleDispatcher
 *
 * @since 2.0
 * @Bean("cliDispatcher")
 */
class ConsoleDispatcher
{
    /**
     * @param array $route
     *
     * @return void
     * @throws ReflectionException
     * @throws Throwable
     */
    public function dispatch(array $route): void
    {
        // Handler info
        [$className, $method] = $route['handler'];

        // Bind method params
        $params = $this->getBindParams($className, $method);
        $object = Swoft::getSingleton($className);

        // Blocking running
        if (!$route['coroutine']) {
            $this->before($method, $className);
            $object->$method(...$params);
            $this->after($method);
            return;
        }

        // Hook php io function
        Runtime::enableCoroutine();

        // If in unit test env, has been in coroutine.
        if (defined('PHPUNIT_COMPOSER_INSTALL')) {
            $this->executeByCo($object, $method, $params);
            return;
        }

        // Coroutine running
        srun(function () use ($object, $method, $params) {
            $this->executeByCo($object, $method, $params);
        });
    }

    /**
     * @param object $object
     * @param string $method
     * @param array  $bindParams
     *
     * @throws Throwable
     */
    public function executeByCo($object, string $method, array $bindParams): void
    {
        try {
            Context::set($ctx = ConsoleContext::new());

            $this->before($method, get_class($object));

            $object->$method(...$bindParams);

            $this->after($method);
        } catch (Throwable $e) {
            /** @var ConsoleErrorDispatcher $errDispatcher */
            $errDispatcher = Swoft::getSingleton(ConsoleErrorDispatcher::class);

            // Handle request error
            $errDispatcher->run($e);
        } finally {
            // Defer
            Swoft::trigger(SwoftEvent::COROUTINE_DEFER);

            // Complete
            Swoft::trigger(SwoftEvent::COROUTINE_COMPLETE);
        }
    }

    /**
     * Get method bounded params
     *
     * @param string $class
     * @param string $method
     *
     * @return array
     * @throws ReflectionException
     */
    private function getBindParams(string $class, string $method): array
    {
        $classInfo = Swoft::getReflection($class);
        if (!isset($classInfo['methods'][$method])) {
            return [];
        }

        // binding params
        $bindParams   = [];
        $methodParams = $classInfo['methods'][$method]['params'];

        /**
         * @var string         $name
         * @var ReflectionType $paramType
         * @var mixed          $devVal
         */
        foreach ($methodParams as [, $paramType, $devVal]) {
            // Defined type of the param
            $type = $paramType->getName();

            if ($type === Output::class) {
                $bindParams[] = Swoft::getBean('output');
            } elseif ($type === Input::class) {
                $bindParams[] = Swoft::getBean('input');
            } else {
                $bindParams[] = null;
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
     *
     * @throws Throwable
     */
    public function before(...$params): void
    {
        // TODO ... event params
        $command = $params[0];
        Swoft::trigger(ConsoleEvent::EXECUTE_BEFORE, $command, $params[1]);
    }

    /**
     * After dispatch
     *
     * @param array $params
     *
     * @throws Throwable
     */
    public function after(...$params): void
    {
        // TODO ... event params
        $command = $params[0];

        Swoft::triggerByArray(ConsoleEvent::EXECUTE_AFTER, $command, [
            'command' => $command,
        ]);
    }
}
