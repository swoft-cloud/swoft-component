<?php

namespace Swoft\Sg\Provider;

/**
 * Provider interface
 */
interface ProviderInterface
{
    /**
     * @param string $serviceName
     * @param array  ...$params
     *
     * @return array
     */
    public function getServiceList(string $serviceName, ...$params): array ;

    /**
     * @param array ...$params
     */
    public function registerService(...$params);
}
