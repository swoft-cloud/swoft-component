<?php

namespace Swoft\Processor;

use Swoft\Console\CommandRegister;
use Swoft\Console\Router\Router;
use Swoft\Log\Helper\CLog;

/**
 * Console processor
 * @since 2.0
 */
class ConsoleProcessor extends Processor
{
    /**
     * Handle console
     * @return bool
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function handle(): bool
    {
        if (!$this->application->beforeConsole()) {
            return false;
        }

        /** @var Router $router */
        $router = \bean('cliRouter');

        // Register console routes
        CommandRegister::register($router);

        CLog::info(
            'Console command route registered (group %d, command %d)',
            $router->count(),
            $router->groupCount()
        );

        // Run console application
        \bean('cliApp')->run();

        return $this->application->afterConsole();
    }
}
