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

namespace Swoft\Sg\Bean\Parser;

use Swoft\Bean\Annotation\Scope;
use Swoft\Bean\Parser\AbstractParser;
use Swoft\Sg\Bean\Annotation\Fallback;
use Swoft\Sg\Bean\Collector\FallbackCollector;

/**
 * Fallback parser
 */
class FallbackParser extends AbstractParser
{
    /**
     * @param string   $className
     * @param Fallback $objectAnnotation
     * @param string   $propertyName
     * @param string   $methodName
     * @param null     $propertyValue
     *
     * @return array
     */
    public function parser(
        string $className,
        $objectAnnotation = null,
        string $propertyName = '',
        string $methodName = '',
        $propertyValue = null
    ): array {
        FallbackCollector::collect($className, $objectAnnotation, $propertyName, $methodName, $propertyValue);

        return [$className, Scope::SINGLETON, ''];
    }
}
