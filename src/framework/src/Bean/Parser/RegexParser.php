<?php

namespace Swoft\Bean\Parser;

use Swoft\Bean\Annotation\Regex;
use Swoft\Bean\Collector\ValidatorCollector;

/**
 * the parser of Regex
 *
 * @uses      RegexParser
 * @version   2018年05月30日
 * @author    leno <leno@itdashu.com>
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class RegexParser extends AbstractParser
{
    /**
     * @param string      $className
     * @param Regex     $objectAnnotation
     * @param string      $propertyName
     * @param string      $methodName
     * @param string|null $propertyValue
     *
     * @return null
     */
    public function parser(
        string $className,
        $objectAnnotation = null,
        string $propertyName = '',
        string $methodName = '',
        $propertyValue = null
    ) {
        ValidatorCollector::collect($className, $objectAnnotation, $propertyName, $methodName, $propertyValue);
        return null;
    }
}
