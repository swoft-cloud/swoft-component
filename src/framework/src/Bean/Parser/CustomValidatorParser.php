<?php
/**
 * This file is part of Swoft.
 *
 * @link https://swoft.org
 * @document https://doc.swoft.org
 * @contact group@swoft.org
 * @license https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Bean\Parser;

use Swoft\Bean\Annotation\CustomValidator;
use Swoft\Bean\Collector\ValidatorCollector;

/**
 * Class CustomValidatorParser
 *
 * @package Swoft\Bean\Parser
 */
class CustomValidatorParser extends AbstractParser
{
    /**
     * @param string          $className
     * @param CustomValidator $objectAnnotation
     * @param string          $propertyName
     * @param string          $methodName
     * @param string|null     $propertyValue
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
