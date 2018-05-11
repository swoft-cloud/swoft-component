<?php

namespace Swoft\Task\Crontab;

use Swoft\Task\Bootstrap\CronTable;
use Swoft\Task\Exception\CronException;

/**
 * CronManager
 */
class CronManager
{
    const CRON_TABLE = 'table';

    /**
     * @var array
     */
    private static $crons = [
            self::CRON_TABLE => CronTable::class,
        ];

    /**
     * Init
     */
    public static function init()
    {
        $cron = self::getCron();
        $cron->initialize();
    }

    /**
     * @return CronInterface
     * @throws CronException
     */
    public static function getCron(): CronInterface
    {
        $cron = self::CRON_TABLE;
        if (!isset(self::$crons[$cron])) {
            throw new CronException(sprintf('The %s driver is not exist!', $cron));
        }

        $beanName = self::$crons[$cron];
        return bean($beanName);
    }

    /**
     * @param string        $name
     * @param CronInterface $cron
     */
    public static function addCron(string $name, CronInterface $cron)
    {
        self::$crons[$name] = $cron;
    }
}