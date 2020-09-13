<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Process;

use Swoft\Process\Annotation\Mapping\Process as ProcessAnnotation;
use Swoft\Process\Exception\ProcessException;

/**
 * Class ProcessRegister
 *
 * @since 2.0
 */
class ProcessRegister
{
    /**
     * @var array
     * @example
     * [
     *     'workerId' => [
     *          'className' => 'className',
     *      ]
     * ]
     */
    private static $process = [];

    /**
     * @var int
     */
    private static $workerId = 0;

    /**
     * @param string $className
     * @param int    $workerNum
     *
     * @throws ProcessException
     */
    public static function registerProcess(string $className, int $workerNum): void
    {
        for ($i = 0; $i < $workerNum; $i ++) {
            self::$process[self::$workerId ++]['class'] = $className;
        }
    }

    /**
     * @param int $workerId
     *
     * @return string
     * @throws ProcessException
     */
    public static function getProcess(int $workerId): string
    {
        $className = self::$process[$workerId]['class'] ?? '';
        if (!empty($className)) {
            return $className;
        }
        throw new ProcessException(sprintf('Worker process(%d) for process pool must be defined!', $workerId));
    }

    /**
     * @return int
     */
    public static function getWorkerNum(): int
    {
        return count(self::$process);
    }
}
