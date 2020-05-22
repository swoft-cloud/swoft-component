<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Processor;

use Dotenv\Dotenv;
use Dotenv\Environment\Adapter\EnvConstAdapter;
use Dotenv\Environment\Adapter\PutenvAdapter;
use Dotenv\Environment\Adapter\ServerConstAdapter;
use Dotenv\Environment\DotenvFactory;
use Swoft;
use Swoft\Log\Helper\CLog;
use Swoft\Stdlib\Helper\Str;
use function basename;
use function dirname;
use function file_exists;
use const IN_PHAR;

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

        $envFile = Swoft::getAlias($this->application->getEnvFile());

        // Fix: In phar package, remove phar:// prefix
        if (IN_PHAR) {
            $envFile = Str::rmPharPrefix($envFile);
        }

        if (!file_exists($envFile)) {
            CLog::warning('Env file(%s) is not exist! skip load it', $envFile);
            return $this->application->afterEnv();
        }

        // Load env info
        $factory = new DotenvFactory([
            new EnvConstAdapter,
            new PutenvAdapter,
            new ServerConstAdapter
        ]);

        $path = dirname($envFile);
        $name = basename($envFile);

        Dotenv::create($path, $name, $factory)->overload();
        CLog::info('Env file(%s) is loaded', $envFile);

        return $this->application->afterEnv();
    }
}
