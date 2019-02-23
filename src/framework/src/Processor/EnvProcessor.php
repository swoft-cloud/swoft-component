<?php

namespace Swoft\Processor;

use Dotenv\Dotenv;

/**
 * Env processor
 *
 * @since 2.0
 */
class EnvProcessor extends Processor
{
    /**
     * Handler env process
     *
     * @return bool
     */
    public function handle(): bool
    {
        if (!$this->application->beforeEnv()) {
            return false;
        }

        $envFile = $this->application->getEnvFile();
        $path    = dirname($envFile);
        $env     = basename($envFile);

        if (!\file_exists($path . $env)) {
            return true;
        }

        // Load env
        $dotenv = new Dotenv($path, $env);
        $dotenv->load();

        return $this->application->afterEvent();
    }
}