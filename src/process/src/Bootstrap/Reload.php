<?php

namespace Swoft\Process\Bootstrap;

use Swoft\App;
use Swoft\Bean\Annotation\Bean;
use Swoft\Bean\Annotation\Inject;
use Swoft\Bean\Annotation\Value;
use Swoft\Helper\FileHelper;

/**
 * 文件更新自动监听
 *
 * @Bean()
 * @uses      Reload
 * @version   2017年08月25日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Reload
{
    /**
     * 监听文件变化的路径
     * @Value(name="${config.reload.watchedDirs}")
     * @var array
     */
    private $watchedDirs = ["./app", "./resources"];

    /**
     * 监听文件后缀名
     * @Value(name="${config.reload.watchedExts}")
     * @var array
     */
    private $watchedExts = ["php"];

    /**
     * the last fingerprint of watched dir and files
     *
     * @var string
     */
    private $lastFingerprint = '';

    /**
     * the interval of scan
     * @Value(name="${config.reload.interval}")
     * @var int
     */
    private $interval = 3;

    /**
     * 初始化方法
     */
    public function init()
    {
        // 把路径转换成相对目录，简化输出信息
        $relativePaths = array_map(function ($path) {
            return str_replace(BASE_PATH . "/", "", realpath($path));
        }, $this->watchedDirs);

        $this->printInfo("Reload process is watching on these dirs and file types:\n"
            . "  dirs : " . join(", ", $relativePaths) . "\n"
            . "  types: " . join(", ", $this->watchedExts) . "\n");

        $this->lastFingerprint = $this->calcFingerprint($this->watchedDirs, $this->watchedExts);
    }


    /**
     * 启动监听
     */
    public function run()
    {
        $server = App::$server;
        while (true) {
            sleep($this->interval);
            $begin = microtime(true);
            $fingerprint = $this->calcFingerprint($this->watchedDirs, $this->watchedExts);

            if (strcmp($this->lastFingerprint, $fingerprint) !== 0) {
                $this->printInfo("Start reloading...");
                $server->getServer()->reload();
                $elapsed = intval((microtime(true) - $begin) * 1000);
                $this->printInfo("Reloaded, cost {$elapsed} ms" );
            }
            $this->lastFingerprint = $fingerprint;
        }
    }


    // 此函数是为了对两种文件监视方式的性能进行对比而保留
    private function calcFingerprintByMd5($watchedDirs, $watchedExts = ['php']) {
        static $warned = false;
        $md5s = [];
        foreach ($watchedDirs as $dir) {
            if(!file_exists($dir)) {
                if(!$warned) {
                    $warned = true;
                    $this->printInfo("WARNING: dir {$dir} is not exists.");
                }
                continue;
            }
            $md5s[] = FileHelper::md5File($dir, $watchedExts);
        }

        $joined = join("", $md5s);
        $fingerprint = md5($joined);

        return $fingerprint;
    }

    private function printInfo($info) {
        $now = date("Y-m-d H:i:s");
//        $this->logger->info($info);
        printf("[%s]: %s\n", $now, $info);
    }

    /**
     * 通过文件最后修改时间来计算指纹，此方法比使用 md5 方式要快很多，消耗 CPU 资源明显减少，
     * 尤其是在机械硬盘上或者有大量文件的情况下。
     *
     * @param array $watchedDirs
     * @param array $watchedExts
     * @return string
     */
    private function calcFingerprint($watchedDirs, $watchedExts = ['php']) {
        static $warned = false;
        $timestamps = [];
        foreach ($watchedDirs as $dir) {
            if(!file_exists($dir)) {
                if(!$warned) {
                    $warned = true; // 如果某个监听的目录不存在，那么在启动的时候打印一次警告
                    $this->printInfo("WARNING: dir {$dir} is not exists.");
                }
                continue;
            }
            $timestamps = array_merge($timestamps, $this->populateTimestamps($dir, $watchedExts));

        }

        $joined = join("", $timestamps);
        $fingerprint = md5($joined); // 集合所有文件信息后只计算一次 MD5，避免无谓的 CPU 消耗

        return $fingerprint;
    }

    /**
     *  get timestamps of watched dirs
     *
     * @param string $dir 要扫描的目录
     * @param array $watchedExts 要扫描的文件后缀名
     *
     * @return bool|string
     */
    private function populateTimestamps($dir, $watchedExts)
    {
        $timestamps = array();
        $d = dir($dir);
        while (false !== ($entry = $d->read())) {
            if ($entry !== '.' && $entry !== '..') {
                $entryPath = $dir . '/' . $entry;
                if (is_dir($entryPath)) {
                    // 递归遍历目录下所有子目录和文件
                    $subfolderTimestamps = $this->populateTimestamps($entryPath, $watchedExts);
                    $timestamps          = array_merge($timestamps, $subfolderTimestamps);
                } elseif (in_array(pathinfo($entry, PATHINFO_EXTENSION), $watchedExts)) {
                    // 收集所有文件最后修改时间，可以保证文件被修改、增删文件都会引起指纹变化
                    $timestamps[] = filemtime($entryPath);
                }
            }
        }
        $d->close();

        return $timestamps;
    }

}
