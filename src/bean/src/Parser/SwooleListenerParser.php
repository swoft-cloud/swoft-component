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
namespace Swoft\Bean\Parser;

use Swoft\Bean\Annotation\Scope;
use Swoft\Bean\Annotation\SwooleListener;
use Swoft\Bean\Collector\SwooleListenerCollector;

/**
 * the parser of swoole listener
 *
 * @uses      SwooleListenerParser
 * @version   2018年01月11日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class SwooleListenerParser extends AbstractParser
{
    /**
     * @param string         $className
     * @param SwooleListener $objectAnnotation
     * @param string         $propertyName
     * @param string         $methodName
     * @param mixed          $propertyValue
     *
     * @return array
     */
    public function parser(string $className, $objectAnnotation = null, string $propertyName = '', string $methodName = '', $propertyValue = null)
    {
        $beanName = $className;
        $scope    = Scope::SINGLETON;

        SwooleListenerCollector::collect($className, $objectAnnotation, $propertyName, $methodName, $propertyValue);

        return [$beanName, $scope, ''];
    }
}
