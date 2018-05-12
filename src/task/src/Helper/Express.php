<?php

namespace Swoft\Task\Helper;

use Swoft\Task\Exception\CronException;

/**
 * Express parser
 */
class Express
{
    /**
     * @var string
     */
    private static $linuxReg = '/^((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)$/i';

    /**
     * @var string
     */
    private static $swoftReg = '/^((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)$/i';

    /**
     * @param string   $express
     * @param int|null $time
     *
     * @return array|bool
     * @throws CronException
     */
    public static function validateExpress(string $express, int $time = null)
    {
        if (!preg_match(self::$linuxReg, trim($express)) && !preg_match(self::$swoftReg, trim($express))) {
            throw new CronException(sprintf('The %s is invalid crontab format', $express));
        }

        $time = ($time === null) ? time() : $time;

        $dateTime = self::formatDateTime($time);
        $cronTime = self::formatCronTime($express);
        if (!is_array($cronTime)) {
            return false;
        }

        return self::matchTime($dateTime, $cronTime);
    }

    /**
     * @param array $dateTime
     * @param array $cronTime
     *
     * @return bool
     */
    public static function matchTime(array $dateTime, array $cronTime)
    {
        $secAndMinResult   = (!$cronTime[0] || in_array($dateTime[0], $cronTime[0])) && (!$cronTime[1] || in_array($dateTime[1], $cronTime[1]));
        $hoursAndDayResult = (!$cronTime[2] || in_array($dateTime[2], $cronTime[2])) && (!$cronTime[3] || in_array($dateTime[3], $cronTime[3]));
        $monAndWeekResult  = (!$cronTime[4] || in_array($dateTime[4], $cronTime[4])) && (!$cronTime[5] || in_array($dateTime[4], $cronTime[5]));

        return $secAndMinResult && $hoursAndDayResult && $monAndWeekResult;
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