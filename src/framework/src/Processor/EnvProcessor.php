<?php

namespace Swoft\Processor;

use Dotenv\Dotenv;
use Swoft\Log\Helper\CLog;

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
            CLog::warning('Stop env processor by beforeEnv return false');
            return false;
        }

        $envFile = $this->application->getEnvFile();
        $envFile = \alias($envFile);
        $path    = \dirname($envFile);
        $env     = \basename($envFile);

        if (!\file_exists($envFile)) {
            CLog::warning('Env file(%s) is not exist! skip load it', $envFile);
            return true;
        }

        // Load env
        $dotenv = new Dotenv($path, $env);
        $dotenv->load();

        CLog::info('Env file(%s) is loaded', $envFile);

        return $this->application->afterEvent();
    }
}
