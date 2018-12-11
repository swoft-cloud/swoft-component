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

use Swoft\App;
use Swoft\Exception\InvalidArgumentException;
use Swoft\Sg\Balancer\BalancerInterface;
use Swoft\Sg\Balancer\RandomBalancer;

/**
 * the manager of balancer
 */
class BalancerSelector implements SelectorInterface
{
    /**
     * the name of random
     */
    const TYPE_RANDOM = 'random';

    /**
     * @var string
     */
    protected $balancer = self::TYPE_RANDOM;

    /**
     * @var array
     */
    protected $balancers = [];

    public function init()
    {
        $this->balancers = \array_merge($this->balancers, $this->defaultBalancers());
    }

    /**
     * get balancer
     *
     * @param string $type
     *
     * @return BalancerInterface
     * @throws \Swoft\Exception\InvalidArgumentException
     */
    public function select(string $type = null): BalancerInterface
    {
        if (empty($type)) {
            $type = $this->balancer;
        }

        $balancers = $this->balancers;

        if (!isset($balancers[$type])) {
            throw new InvalidArgumentException(sprintf('Balancer %s does not exist', $type));
        }

        $balancerBeanName = $balancers[$type];

        return App::getBean($balancerBeanName);
    }

    /**
     * @return array
     */
    public function getBalancers(): array
    {
        return $this->balancers;
    }

    /**
     * @param array $balancers
     */
    public function setBalancers(array $balancers)
    {
        $this->balancers = $balancers;
    }

    /**
     * the balancers of default
     *
     * @return array
     */
    private function defaultBalancers(): array
    {
        return [
            self::TYPE_RANDOM => RandomBalancer::class,
        ];
    }
}
