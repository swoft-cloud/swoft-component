<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Bean\Parser;

use Swoft\Bean\Annotation\CustomMethod;
use Swoft\Bean\Collector;

/**
 * the parser of cacheable
 *
 * @uses      CustomMethodParser
 * @version   2018年07月08日
 * @author    limx <715557344@qq.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class CustomMethodParser extends AbstractParser
{
    /**
     * RequestMapping注解解析
     *
     * @param string $className
     * @param CustomMethod $objectAnnotation
     * @param string $propertyName
     * @param string $methodName
     * @param null $propertyValue
     * @return mixed
     */
    public function parser(string $className, $objectAnnotation = null, string $propertyName = '', string $methodName = '', $propertyValue = null)
    {
        Collector::$methodAnnotations[$className][$methodName][] = get_class($objectAnnotation);
        return null;
    }
}
