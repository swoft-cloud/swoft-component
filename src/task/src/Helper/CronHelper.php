<?php

namespace Swoft\Task\Helper;

use Swoft\App;
use Swoft\Task\Exception\CronException;

/**
 * CronHelper
 */
class CronHelper
{
    /**
     * @var string
     */
    private static $cronReg = '/^((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)$/i';

    /**
     * Parse string
     *
     * @param string $cronString :
     *
     *      0     1    2    3    4    5
     *      *     *    *    *    *    *
     *      -     -    -    -    -    -
     *      |     |    |    |    |    |
     *      |     |    |    |    |    +----- day of week (0 - 6) (Sunday=0)
     *      |     |    |    |    +----- month (1 - 12)
     *      |     |    |    +------- day of month (1 - 31)
     *      |     |    +--------- hour (0 - 23)
     *      |     +----------- min (0 - 59)
     *      +------------- sec (0-59)
     * @param int    $startTime  timestamp [default=current timestamp]
     *
     * @throws \Swoft\Task\Exception\CronException
     * @return array unix timestamp - 下一分钟内执行是否需要执行任务，如果需要，则把需要在那几秒执行返回
     */
    public static function parse(string $cronString, int $startTime = null): array
    {
        $cronString = str_replace("\\", '', $cronString);
        if (!preg_match(self::$cronReg, trim($cronString))) {
            throw new CronException(sprintf('The %s is invalid crontab format', $cronString));
        }

        if ($startTime && !is_numeric($startTime)) {
            throw new CronException(sprintf('Startime must be a valid unix timestamp (%d given)', $startTime));
        }

        $cron  = preg_split("/[\s]+/i", trim($cronString));
        $start = empty($startTime) ? time() : $startTime;

        $date = [
            'second'  => (empty($cron[0])) ? [1 => 1] : self::parseCronNumber($cron[0], 0, 59),
            'minutes' => self::parseCronNumber($cron[1], 0, 59),
            'hours'   => self::parseCronNumber($cron[2], 0, 23),
            'day'     => self::parseCronNumber($cron[3], 1, 31),
            'month'   => self::parseCronNumber($cron[4], 1, 12),
            'week'    => self::parseCronNumber($cron[5], 0, 6),
        ];

        $isMonth      = in_array(intval(date('n', $start)), $date['month']);
        $isMinAndHour = in_array(intval(date('i', $start)), $date['minutes']) && in_array(intval(date('G', $start)), $date['hours']);
        $isDayAndWeek = in_array(intval(date('j', $start)), $date['day']) && in_array(intval(date('w', $start)), $date['week']);

        if ($isMinAndHour && $isDayAndWeek && $isMonth) {
            return $date['second'];
        }

        return [];
    }

    /**
     * @param string $s
     * @param int    $min
     * @param int    $max
     *
     * @return array
     */
    private static function parseCronNumber($s, $min, $max): array
    {
        $result = array();
        $v1     = explode(",", $s);
        foreach ($v1 as $v2) {
            $v3   = explode("/", $v2);
            $step = empty($v3[1]) ? 1 : $v3[1];
            $v4   = explode("-", $v3[0]);
            $_min = count($v4) == 2 ? $v4[0] : ($v3[0] == "*" ? $min : $v3[0]);
            $_max = count($v4) == 2 ? $v4[1] : ($v3[0] == "*" ? $max : $v3[0]);
            for ($i = $_min; $i <= $_max; $i += $step) {
                if (intval($i) < $min) {
                    $result[$min] = $min;
                } elseif (intval($i) > $max) {
                    $result[$max] = $max;
                } else {
                    $result[$i] = intval($i);
                }
            }
        }

        ksort($result);

        return $result;
    }

    /**
     * @return bool
     */
    public static function isCronable(): bool
    {
        if (App::$server === null) {
            return false;
        }

        $serverSetting = App::$server->getServerSetting();
        $cronable      = (bool)$serverSetting['cronable'];

        return $cronable;
    }
}