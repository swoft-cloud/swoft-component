<?php

namespace Swoft\Processor;

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

        return $this->application->afterConfig();
    }
}