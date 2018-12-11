<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Rpc\Client\Bean\Parser;

use Swoft\Bean\Parser\AbstractParser;
use Swoft\Rpc\Client\Bean\Annotation\Reference;
use Swoft\Rpc\Client\Bean\Collector\ReferenceCollector;

/**
 * The parser of reference
 */
class ReferenceParser extends AbstractParser
{
    /**
     * @param string $className
     * @param Reference $objectAnnotation
     * @param string $propertyName
     * @param string $methodName
     * @param null $propertyValue
     * @return array
     * @throws \Swoft\Rpc\Client\Exception\RpcClientException
     */
    public function parser(string $className, $objectAnnotation = null, string $propertyName = '', string $methodName = '', $propertyValue = null): array
    {
        $referenceClass = ReferenceCollector::collect($className, $objectAnnotation, $propertyName, $methodName, $propertyValue);
        return [$referenceClass, true];
    }
}
