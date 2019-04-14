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
        $appDebug = \env('APP_DEBUG', false);
        \define('APP_DEBUG', (bool)$appDebug);

        $sysDebug = \env('SWOFT_DEBUG', false);
        \define('SWOFT_DEBUG', (bool)$sysDebug);

        return $this->application->afterConfig();
    }
}
