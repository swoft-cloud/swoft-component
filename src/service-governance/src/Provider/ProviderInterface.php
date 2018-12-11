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
