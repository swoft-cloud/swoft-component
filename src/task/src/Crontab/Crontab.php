<?php

namespace Swoft\Task\Crontab;

use Swoft\Bean\Annotation\Bean;

/**
 * @Bean()
 */
class Crontab implements CronInterface
{
    /**
     * Init
     */
    public function initialize()
    {

    }

    /**
     * Consume task
     */
    public function consume()
    {
        swoole_timer_tick(0.5 * 1000, function () {
            CronManager::getCron()->consume();
        });
    }

    /**
     * Produce task
     */
    public function produce()
    {
        CronManager::getCron()->produce();
        swoole_timer_tick(60 * 1000, function () {
            var_dump('produce-before='.time());
            CronManager::getCron()->produce();
            var_dump('produce-after='.time());
        });
    }
}