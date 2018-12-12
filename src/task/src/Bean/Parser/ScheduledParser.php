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

namespace Swoft\Task\Bean\Parser;

use Swoft\Bean\Parser\AbstractParser;
use Swoft\Task\Bean\Annotation\Scheduled;
use Swoft\Task\Bean\Collector\TaskCollector;

/**
 * Scheduled annotation parser
 */
class ScheduledParser extends AbstractParser
{
    /**
     * @param string    $className
     * @param Scheduled $objectAnnotation
     * @param string    $propertyName
     * @param string    $methodName
     * @param null      $propertyValue
     */
    public function parser(
        string $className,
        $objectAnnotation = null,
        string $propertyName = '',
        string $methodName = '',
        $propertyValue = null
    ) {
        TaskCollector::collect($className, $objectAnnotation, $propertyName, $methodName, $propertyValue);
    }
}
