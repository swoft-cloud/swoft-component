<?php

namespace Swoft\Processor;

use Swoft\Console\Application;
use Swoft\Console\Bean\Parser\CommandParser;
use Swoft\Console\Router\Router;
use Swoft\Http\Server\HttpServer;

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
//        $router = \bean('cliRouter');

        // Register console routes
//        CommandParser::registerTo($router);

        // Run console application
        /** @var Application $cliApp */
//        $cliApp = \bean('cliApp');
//        $cliApp->run();

        /* @var HttpServer $httpServer */
         $httpServer = bean('httpServer');
         $httpServer->start();

        return $this->application->afterConsole();
    }
}
