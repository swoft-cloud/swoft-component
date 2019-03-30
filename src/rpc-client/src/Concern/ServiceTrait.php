<?php declare(strict_types=1);


namespace Swoft\Rpc\Client\Concern;

/**
 * Class ServiceTrait
 *
 * @since 2.0
 */
trait ServiceTrait
{
    /**
     * @param string $interfaceClass
     * @param string $methodName
     * @param array  $params
     *
     * @return array
     */
    private function __proxyCall(string $interfaceClass, string $methodName, array $params)
    {
        return [$interfaceClass, $methodName, $params];
    }
}