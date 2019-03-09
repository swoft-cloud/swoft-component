<?php

namespace Swoft\Processor;

use Swoft\Console\Annotation\Parser\CommandParser;
use Swoft\Console\Application;
use Swoft\Console\Router\Router;

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
        CommandParser::registerTo($router);

        // Run console application
        \bean('cliApp')->run();

        return $this->application->afterConsole();
    }
}
