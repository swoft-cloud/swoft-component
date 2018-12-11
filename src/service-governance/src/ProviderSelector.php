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

namespace Swoft\Sg;

use Swoft\Exception\InvalidArgumentException;
use Swoft\Sg\Provider\ConsulProvider;
use Swoft\Sg\Provider\ProviderInterface;

/**
 * Provider selector
 */
class ProviderSelector implements SelectorInterface
{
    /**
     * consul
     */
    const TYPE_CONSUL = 'consul';

    /**
     * Default provider
     *
     * @var string
     */
    protected $provider = self::TYPE_CONSUL;

    /**
     * @var array
     */
    protected $providers = [];

    public function init()
    {
        $this->providers = \array_merge($this->providers, $this->defaultProviders());
    }

    /**
     * Select a provider by Selector
     *
     * @param string $type
     * @return ProviderInterface
     * @throws \Swoft\Exception\InvalidArgumentException
     */
    public function select(string $type = null): ProviderInterface
    {
        if (empty($type)) {
            $type = $this->provider;
        }

        $providers = $this->providers;
        if (!isset($providers[$type])) {
            throw new InvalidArgumentException(sprintf('Provider %s does not exist', $type));
        }

        $providerBeanName = $providers[$type];

        return \bean($providerBeanName);
    }

    /**
     * @return array
     */
    public function getProviders(): array
    {
        return $this->providers;
    }

    /**
     * @param array $providers
     */
    public function setProviders(array $providers)
    {
        $this->providers = $providers;
    }

    /**
     * the balancers of default
     *
     * @return array
     */
    private function defaultProviders(): array
    {
        return [
            self::TYPE_CONSUL => ConsulProvider::class,
        ];
    }
}
