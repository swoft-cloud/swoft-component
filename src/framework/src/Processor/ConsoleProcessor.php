<?php

namespace Swoft\Processor;

use App\Aspect\TestLog;
use Swoft\Http\Server\HttpServer;

/**
 * Console processor
 */
class ConsoleProcessor extends Processor
{
    /**
     * Handle console
     */
    public function handle(): bool
    {
        if (!$this->application->beforeConfig()) {
            return false;
        }

        echo 'console' . PHP_EOL;
        echo '---------------------' . PHP_EOL;

        /** @var TestLog $testLog */
        $testLog = bean('testLog');
        echo $testLog->log() . PHP_EOL;

        /* @var HttpServer $httpServer */
        $httpServer = bean('httpServer');
        $httpServer->start();

        return $this->application->afterConfig();
    }
}