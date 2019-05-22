<?php declare(strict_types=1);


namespace Swoft\Rpc\Client\Contract;

/**
 * Class ProviderInterface
 *
 * @since 2.0
 */
interface ProviderInterface
{
    /**
     * @return array
     *
     * @example
     * [
     *     'host:port',
     *     'host:port',
     *     'host:port',
     * ]
     */
    public function getList(): array;
}