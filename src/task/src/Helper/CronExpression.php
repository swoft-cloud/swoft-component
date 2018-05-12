<?php

namespace Swoft\Task\Helper;

use Swoft\Task\Exception\CronException;

/**
 * Expression parser
 */
class CronExpression
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
     * @param string   $expression
     * @param int|null $time
     *
     * @return array|bool
     * @throws CronException
     */
    public static function validateExpression(string $expression, int $time = null)
    {
        if (!preg_match(self::$linuxReg, trim($expression)) && !preg_match(self::$swoftReg, trim($expression))) {
            throw new CronException(sprintf('The %s is invalid crontab format', $expression));
        }

        $time = ($time === null) ? time() : $time;

        $dateTime = self::formatDateTime($time);
        $cronTime = self::formatCronTime($expression);
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
     * @param string $expression
     *
     * @return array
     */
    static public function formatCronTime(string $expression): array
    {
        $expression = trim($expression);

        $cronTime = [];
        $parts    = explode(' ', $expression);
        if (count($parts) == 5) {
            array_unshift($parts, '0');
        }

        // Format: `second minute hours day month week`
        $cronTime[0] = self::parseExpressionBlock($parts[0], 0, 59);
        $cronTime[1] = self::parseExpressionBlock($parts[1], 0, 59);
        $cronTime[2] = self::parseExpressionBlock($parts[2], 0, 23);
        $cronTime[3] = self::parseExpressionBlock($parts[3], 1, 31);
        $cronTime[4] = self::parseExpressionBlock($parts[4], 1, 12);
        $cronTime[5] = self::parseExpressionBlock($parts[5], 0, 6);

        return $cronTime;
    }

    /**
     * @param string $expressionBlock
     * @param int    $rangeLeft
     * @param int    $rangeRight
     *
     * @return array
     * @throws CronException
     */
    private static function parseExpressionBlock(string $expressionBlock, int $rangeLeft, int $rangeRight): array
    {
        $list = array();

        // Delimiter `,`
        if (false !== strpos($expressionBlock, ',')) {
            $arr = explode(',', $expressionBlock);
            foreach ($arr as $v) {
                $tmp  = self::parseExpressionBlock($v, $rangeLeft, $rangeRight);
                $list = array_merge($list, $tmp);
            }

            return $list;
        }

        // Delimiter `/`
        $tmp             = explode('/', $expressionBlock);
        $expressionBlock = $tmp[0];
        $step            = isset($tmp[1]) ? $tmp[1] : 1;

        // Delimiter `-`
        if (false !== strpos($expressionBlock, '-')) {
            list($min, $max) = explode('-', $expressionBlock);
            if ($min > $max) {
                throw new CronException('Min can\'t be greater than max');
            }
        } elseif ('*' == $expressionBlock) {
            $min = $rangeLeft;
            $max = $rangeRight;
        } else {
            $min = $max = $expressionBlock;
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