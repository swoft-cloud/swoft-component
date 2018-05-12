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
     * @param bool $isFirst
     *
     * @return void
     */
    public function produce(bool $isFirst = false);

    /**
     * @return void
     */
    public function consume();
}