<?php

namespace Swoft\Processor;

/**
 * Config processor
 * @since 2.0
 */
class ConfigProcessor extends Processor
{
    /**
     * Handle config
     */
    public function handle(): bool
    {
        if (!$this->application->beforeConfig()) {
            return false;
        }

        return $this->application->afterConfig();
    }
}
