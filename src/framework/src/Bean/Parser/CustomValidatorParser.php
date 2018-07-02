<?php

namespace Swoft\Bean\Parser;

use Swoft\Bean\Annotation\CustomValidator;
use Swoft\Bean\Collector\ValidatorCollector;

/**
 * the parser of custom validator
 *
 * @uses      CustomValidatorParser
 * @version   2018年07月02日
 * @author    limx <715557344@qq.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class CustomValidatorParser extends AbstractParser
{
    /**
     * @param string          $className
     * @param CustomValidator $objectAnnotation
     * @param string          $propertyName
     * @param string          $methodName
     * @param string|null     $propertyValue
     *
     * @return null
     */
    public function parser(
        string $className,
        $objectAnnotation = null,
        string $propertyName = '',
        string $methodName = '',
        $propertyValue = null
    )
    {
        ValidatorCollector::collect($className, $objectAnnotation, $propertyName, $methodName, $propertyValue);
        return null;
    }
}
