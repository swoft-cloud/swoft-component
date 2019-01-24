<?php

namespace Swoft\Processor;

use App\Aspect\TestLog;

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

        return $this->application->afterConfig();
    }
}