<?php

namespace Swoft\Console;

use Swoft\App;
use Swoft\Console\Bean\Collector\CommandCollector;

class Console implements ConsoleInterface
{

    public function __construct()
    {
        $this->registerMapping();
    }

    /**
     * @throws \ReflectionException
     * @throws \InvalidArgumentException
     */
    public function run()
    {
        try {
            /* @var \Swoft\Console\Command $command */
            $command = App::getBean('command');
            $command->run();
        } catch (\Throwable $e) {
            \output()->writeln(sprintf('<error>%s</error>', $e->getMessage()), true, false);
            \output()->writeln(sprintf("Trace:\n%s", $e->getTraceAsString()), true, true);
        }
    }

    private function registerMapping()
    {
        /* @var \Swoft\Console\Router\HandlerMapping $route */
        $route = App::getBean('commandRoute');
        $route->register(CommandCollector::getCollector());
    }
}
