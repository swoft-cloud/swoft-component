<?php

namespace Swoft\Bootstrap\Boots;

use Dotenv\Dotenv;
use Swoft\App;
use Swoft\Bean\Annotation\Bootstrap;

/**
 * @Bootstrap(order=1)
 */
class LoadEnv implements Bootable
{
    /**
     * @throws \InvalidArgumentException
     */
    public function bootstrap()
    {
        if ($this->isAvailableFile($file = $this->getEnvFile())) {
            (new Dotenv($this->getEnvBaseDir(), $file))->load();
        }
    }

    /**
     * @return string
     */
    protected function getEnvFile(): string
    {
        $baseDir = $this->getEnvBaseDir();
        $appEnv = env('APP_ENV');
        $file = '.env';
        if ($appEnv && $this->isAvailableFile($baseDir . DS . '.env.' . $appEnv)) {
            $file .= '.' . $appEnv;
        }
        return $file;
    }

    /**
     * @param $file
     * @return bool
     */
    protected function isAvailableFile($file): bool
    {
        return \file_exists($file) && \is_readable($file);
    }

    /**
     * @return string
     */
    protected function getEnvBaseDir(): string
    {
        return App::hasAlias('@env') ? alias('@env', '') : alias('@root', '');
    }
}
