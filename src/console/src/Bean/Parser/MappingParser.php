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

namespace Swoft\Console\Bean\Parser;

use Swoft\Bean\Parser\AbstractParser;
use Swoft\Console\Bean\Collector\CommandCollector;

class MappingParser extends AbstractParser
{
    public function parser(
        string $className,
        $objectAnnotation = null,
        string $propertyName = '',
        string $methodName = '',
        $propertyValue = null
    ) {
        CommandCollector::collect($className, $objectAnnotation, $propertyName, $methodName, $propertyValue);
    }
}
