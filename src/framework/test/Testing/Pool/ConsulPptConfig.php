<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace SwoftTest\Testing\Pool;

use Swoft\Bean\Annotation\Bean;
use Swoft\Bean\Annotation\Value;

/**
 * the properties of config
 *
 * @Bean
 */
class ConsulPptConfig
{
    /**
     * adress
     *
     * @Value(name="${config.provider.consul.address}")
     * @var string
     */
    protected $address = 'http://127.0.0.1:80';

    /**
     * the tags of register service
     *
     * @Value(name="${config.provider.consul.tags}")
     * @var array
     */
    protected $tags = [];

    /**
     * the timeout of consul
     *
     * @Value(name="${config.provider.consul.timeout}")
     * @var int
     */
    protected $timeout = 300;

    /**
     * the interval of register service
     *
     * @Value(name="${config.provider.consul.interval}")
     * @var int
     */
    protected $interval = 3;

    public function getServiceList(string $serviceName, ...$params)
    {
        // TODO: Implement getServiceList() method.
    }

    public function registerService(...$params)
    {
        // TODO: Implement registerService() method.
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * @return int
     */
    public function getInterval(): int
    {
        return $this->interval;
    }
}
