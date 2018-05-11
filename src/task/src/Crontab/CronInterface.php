<?php

namespace Swoft\Task\Crontab;

/**
 * CronInterface
 */
interface CronInterface
{
    /**
     * @return void
     */
    public function initialize();

    /**
     * @return void
     */
    public function produce();

    /**
     * @return void
     */
    public function consume();
}