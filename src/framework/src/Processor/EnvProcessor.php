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

        $env  = $this->application->getEnv();
        $path = \BASE_PATH . DIRECTORY_SEPARATOR;

        if (!\file_exists($path . $env)) {
            return true;
        }

        // Load env
        $dotenv = new Dotenv($path, $env);
        $dotenv->load();

        return $this->application->afterEvent();
    }
}