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

        // Define some global constants
        $appDebug = \env('APP_DEBUG', true);
        \define('APP_DEBUG', (bool)$appDebug);

        $sysDebug = \env('SWOFT_DEBUG', true);
        \define('SWOFT_DEBUG', (bool)$sysDebug);

        return $this->application->afterConfig();
    }
}
