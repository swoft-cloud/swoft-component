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

use Swoft\Bean\Annotation\Scope;
use Swoft\Bean\Parser\AbstractParser;
use Swoft\Task\Bean\Annotation\Task;
use Swoft\Task\Bean\Collector\TaskCollector;

/**
 * Task annotation parser
 */
class TaskParser extends AbstractParser
{
    /**
     * @param string $className
     * @param Task   $objectAnnotation
     * @param string $propertyName
     * @param string $methodName
     * @param null   $propertyValue
     * @return array
     */
    public function parser(
        string $className,
        $objectAnnotation = null,
        string $propertyName = '',
        string $methodName = '',
        $propertyValue = null
    ): array {
        $name = $objectAnnotation->getName();
        $beanName = empty($name) ? $className : $name;

        TaskCollector::collect($className, $objectAnnotation, $propertyName, $methodName, $propertyValue);
        return [$beanName, Scope::SINGLETON, ''];
    }
}
