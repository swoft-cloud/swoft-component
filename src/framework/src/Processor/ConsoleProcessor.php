<?php

namespace Swoft\Processor;

use Swoft\Console\Bean\Parser\CommandParser;
use Swoft\Console\Router\Router;
use Swoft\Http\Server\HttpServer;

/**
 * Console processor
 */
class ConsoleProcessor extends Processor
{
    /**
     * Handle console
     * @return bool
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Server\Exception\ServerException
     */
    public function handle(): bool
    {
        if (!$this->application->beforeConsole()) {
            return false;
        }

        /** @var Router $router */
        $router = \bean('cliRouter');

        // register command routes
        CommandParser::registerTo($router);

        /* @var HttpServer $httpServer */
        $httpServer = bean('httpServer');
        $httpServer->start();

        return $this->application->afterConsole();
    }
}
