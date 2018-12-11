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
namespace SwoftTest\Testing\Pool;

use Swoft\Bean\Annotation\Bean;
use Swoft\Bean\Annotation\Value;

/**
 * the properties of config
 *
 * @Bean
 */
class ConsulEnvConfig
{
    /**
     * adress
     *
     * @Value(env="${PROVIDER_CONSUL_ADDRESS}")
     * @var string
     */
    protected $address = 'http://127.0.0.1:80';

    /**
     * the tags of register service
     *
     * @Value(env="${PROVIDER_CONSUL_TAGS}")
     * @var array
     */
    protected $tags = [];

    /**
     * the timeout of consul
     *
     * @Value(env="${PROVIDER_CONSUL_TIMEOUT}")
     * @var int
     */
    protected $timeout = 300;

    /**
     * the interval of register service
     *
     * @Value(env="${PROVIDER_CONSUL_INTERVAL}")
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
