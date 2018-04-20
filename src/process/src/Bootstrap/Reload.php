<?php

namespace Swoft\Process\Bootstrap;

use Swoft\App;
use Swoft\Bean\Annotation\Bean;
use Swoft\Helper\FileHelper;

/**
 * 文件更新自动监听
 *
 * @Bean()
 */
class Reload
{
    /**
     * 监听文件变化的路径
     *
     * @var string
     */
    private $watchDir;

    /**
     * the lasted md5 of dir
     *
     * @var string
     */
    private $md5File = '';

    /**
     * the interval of scan
     *
     * @var int
     */
    private $interval = 3;

    /**
     * @throws \InvalidArgumentException
     */
    public function init()
    {
        $this->watchDir = App::getAlias('@app');
        $this->md5File = FileHelper::md5File($this->watchDir);
    }


    /**
     * 启动监听
     */
    public function run()
    {
        $server = App::$server;
        while (true) {
            sleep($this->interval);
            $md5File = FileHelper::md5File($this->watchDir);
            if (strcmp($this->md5File, $md5File) !== 0) {
                echo "Start reloading...\n";
                $server->isRunning();
                $server->getServer()->reload();
                echo "Reloaded\n";
            }
            $this->md5File = $md5File;
        }
    }
}
