<?php

namespace Swoft\Task\Helper;

use Swoft\Task\Exception\CronException;

/**
 * Express parser
 */
class Express
{
    /**
     * @param string   $sexpress
     * @param int|null $time
     *
     * @return array|bool
     */
    public static function validateExpress(string $sexpress, int $time = null)
    {
        $time = ($time === null) ? time() : $time;

        $dateTime = self::formatDateTime($time);
        $cronTime = self::formatCronTime($sexpress);
        if (!is_array($cronTime)) {
            return false;
        }

        return self::matchTime($dateTime, $cronTime);
    }

    /**
     * 使用格式化的数据检查某时间($format_time)是否符合某个corntab时间计划($format_cron)
     *
     * @param array $format_time self::format_timestamp()格式化时间戳得到
     * @param array $format_cron self::format_crontab()格式化的时间计划
     *
     * @return bool
     */
    static public function matchTime(array $format_time, array $format_cron)
    {
        return (!$format_cron[0] || in_array($format_time[0], $format_cron[0]))
            && (!$format_cron[1] || in_array($format_time[1], $format_cron[1]))
            && (!$format_cron[2] || in_array($format_time[2], $format_cron[2]))
            && (!$format_cron[3] || in_array($format_time[3], $format_cron[3]))
            && (!$format_cron[4] || in_array($format_time[4], $format_cron[4]))
            && (!$format_cron[5] || in_array($format_time[4], $format_cron[5]));
    }

    /**
     * @param string $express
     *
     * @return array
     */
    static public function formatCronTime(string $express): array
    {
        $express = trim($express);

        $cronTime = [];
        $parts    = explode(' ', $express);
        if (count($parts) == 5) {
            array_unshift($parts, '0');
        }

        // Format: `second minute hours day month week`
        $cronTime[0] = self::parseExpressBlock($parts[0], 0, 59);
        $cronTime[1] = self::parseExpressBlock($parts[1], 0, 59);
        $cronTime[2] = self::parseExpressBlock($parts[2], 0, 23);
        $cronTime[3] = self::parseExpressBlock($parts[3], 1, 31);
        $cronTime[4] = self::parseExpressBlock($parts[4], 1, 12);
        $cronTime[5] = self::parseExpressBlock($parts[5], 0, 6);

        return $cronTime;
    }

    /**
     * @param string $expressBlock
     * @param int    $rangeLeft
     * @param int    $rangeRight
     *
     * @return array
     * @throws CronException
     */
    private static function parseExpressBlock(string $expressBlock, int $rangeLeft, int $rangeRight): array
    {
        $list = array();

        // Delimiter `,`
        if (false !== strpos($expressBlock, ',')) {
            $arr = explode(',', $expressBlock);
            foreach ($arr as $v) {
                $tmp  = self::parseExpressBlock($v, $rangeLeft, $rangeRight);
                $list = array_merge($list, $tmp);
            }

            return $list;
        }

        // Delimiter `/`
        $tmp          = explode('/', $expressBlock);
        $expressBlock = $tmp[0];
        $step         = isset($tmp[1]) ? $tmp[1] : 1;

        // Delimiter `-`
        if (false !== strpos($expressBlock, '-')) {
            list($min, $max) = explode('-', $expressBlock);
            if ($min > $max) {
                throw new CronException('Min can\'t be greater than max');
            }
        } elseif ('*' == $expressBlock) {
            $min = $rangeLeft;
            $max = $rangeRight;
        } else {
            $min = $max = $expressBlock;
        }

        // Any number
        if ($min == $rangeLeft && $max == $rangeRight && $step == 1) {
            return $list;
        }

        // Not within the scope
        if ($min < $rangeLeft || $max > $rangeRight) {
            throw new CronException('It must be within the scope, second(0-59) minute(0-59) hours(0-23) day(1-31) month(1-12) week(0-6)');
        }

        return $max - $min > $step ? range($min, $max, $step) : array((int)$min);
    }

    /**
     * @param int $time
     *
     * @return array
     */
    public static function formatDateTime(int $time): array
    {
        return explode('-', date('s-i-G-j-n-w', $time));
    }
}