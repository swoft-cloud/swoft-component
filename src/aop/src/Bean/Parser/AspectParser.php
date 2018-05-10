<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Aop\Bean\Parser;

use Swoft\Aop\Bean\Annotation\Aspect;
use Swoft\Bean\Annotation\Scope;
use Swoft\Aop\Bean\Collector\AspectCollector;

/**
 * Class AspectParser
 *
 * @package Swoft\Aop\Bean\Parser
 */
class AspectParser extends AbstractParser
{
    /**
     * aspect parsing
     *
     * @param string $className
     * @param Aspect $objectAnnotation
     * @param string $propertyName
     * @param string $methodName
     * @param null   $propertyValue
     *
     * @return mixed
     */
    public function parser(string $className, $objectAnnotation = null, string $propertyName = '', string $methodName = '', $propertyValue = null)
    {
        $beanName = $className;
        $scope    = Scope::SINGLETON;
        AspectCollector::collect($className, $objectAnnotation, $propertyName, $methodName, $propertyValue);

        return [$beanName, $scope, ''];
    }
}
