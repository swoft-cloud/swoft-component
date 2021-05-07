<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Redis\Contract;

/**
 * Class ConnectorInterface
 *
 * @since 2.0
 */
interface ConnectorInterface
{
    /**
     * @param array $config
     * @param array $option
     *
     * @return Object
     */
    public function connect(array $config, array $option);

    /**
     * @param array $config
     * @param array $option
     *
     * @return Object
     */
    public function connectToCluster(array $config, array $option);
}
