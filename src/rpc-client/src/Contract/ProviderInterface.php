<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Rpc\Client\Contract;

use Swoft\Rpc\Client\Client;

/**
 * Class ProviderInterface
 *
 * @since 2.0
 */
interface ProviderInterface
{
    /**
     * @param Client $client
     *
     * @return array
     *
     * @example
     * [
     *     'host:port',
     *     'host:port',
     *     'host:port',
     * ]
     */
    public function getList(Client $client): array;
}
