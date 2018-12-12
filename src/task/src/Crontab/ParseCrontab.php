<?php
declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Task\Crontab;

class ParseCrontab
{
    /**
     * @var string 错误信息
     */
    public static $error;

    /**
     *  解析crontab的定时格式，linux只支持到分钟/，这个类支持到秒
     *
     * @param string $crontab_string :
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
     * @param int    $start_time     timestamp [default=current timestamp]
     *
     * @return int|null unix timestamp - 下一分钟内执行是否需要执行任务，如果需要，则把需要在那几秒执行返回
     */
    public static function parse($crontab_string, $start_time = null)
    {
        $crontab_string = str_replace('\\', '', $crontab_string);
        if (!preg_match(
            '/^((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)$/i',
            trim($crontab_string)
        )
        ) {
            if (!preg_match(
                '/^((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)$/i',
                trim($crontab_string)
            )
            ) {
                self::$error = 'Invalid cron string: ' . $crontab_string;
                return false;
            }
        }
        if ($start_time && !is_numeric($start_time)) {
            self::$error = "\$start_time must be a valid unix timestamp ($start_time given)";
            return false;
        }
        $cron = preg_split("/[\s]+/i", trim($crontab_string));
        $start = empty($start_time) ? time() : $start_time;

        if (\count($cron) === 6) {
            $date = [
                'second'  => empty($cron[0]) ? [1 => 1] : self::parseCronNumber($cron[0], 0, 59),
                'minutes' => self::parseCronNumber($cron[1], 0, 59),
                'hours'   => self::parseCronNumber($cron[2], 0, 23),
                'day'     => self::parseCronNumber($cron[3], 1, 31),
                'month'   => self::parseCronNumber($cron[4], 1, 12),
                'week'    => self::parseCronNumber($cron[5], 0, 6),
            ];
        } elseif (\count($cron) === 5) {
            $date = [
                'second'  => [1 => 1],
                'minutes' => self::parseCronNumber($cron[0], 0, 59),
                'hours'   => self::parseCronNumber($cron[1], 0, 23),
                'day'     => self::parseCronNumber($cron[2], 1, 31),
                'month'   => self::parseCronNumber($cron[3], 1, 12),
                'week'    => self::parseCronNumber($cron[4], 0, 6),
            ];
        }
        if (\in_array((int)date('i', $start), $date['minutes'], true)
            && \in_array((int)date('G', $start), $date['hours'], true)
            && \in_array((int)date('j', $start), $date['day'], true)
            && \in_array((int)date('w', $start), $date['week'], true)
            && \in_array((int)date('n', $start), $date['month'], true)
        ) {
            return $date['second'];
        }

        return null;
    }

    /**
     * 解析单个配置的含义
     *
     * @param string $s   时间
     * @param int    $min 最小值
     * @param int    $max 最大值
     *
     * @return array
     */
    private static function parseCronNumber($s, $min, $max): array
    {
        $result = [];
        $v1 = explode(',', $s);
        foreach ($v1 as $v2) {
            $v3 = explode('/', $v2);
            $step = empty($v3[1]) ? 1 : $v3[1];
            $v4 = explode('-', $v3[0]);
            $_min = \count($v4) === 2 ? $v4[0] : ($v3[0] === '*' ? $min : $v3[0]);
            $_max = \count($v4) === 2 ? $v4[1] : ($v3[0] === '*' ? $max : $v3[0]);
            for ($i = $_min; $i <= $_max; $i += $step) {
                if ($i < $min) {
                    $result[$min] = $min;
                } elseif ($i > $max) {
                    $result[$max] = $max;
                } else {
                    $result[$i] = (int)$i;
                }
            }
        }

        ksort($result);

        return $result;
    }
}
