<?php

namespace Swoft\Bootstrap\Boots;

use Dotenv\Dotenv;
use Swoft\App;
use Swoft\Bean\Annotation\Bootstrap;

/**
 * @Bootstrap(order=1)
 * @author    huangzhhui <huangzhwork@gmail.com>
 */
class LoadEnv implements Bootable
{
    /**
     * @throws \InvalidArgumentException
     */
    public function bootstrap()
    {
        $file = '.env';
        $base_dir = boolval(\Phar::running(false)) ? dirname(\Phar::running(false)) : App::getAlias('@root');
        $filePath = $base_dir . DS . $file;

        if (\file_exists($filePath) && \is_readable($filePath)) {
            (new Dotenv($base_dir, $file))->load();
        }
    }
}
