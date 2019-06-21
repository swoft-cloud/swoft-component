<?php declare(strict_types=1);


namespace SwoftTest\Component\Testing\Config;

use ReflectionException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Exception\ContainerException;

/**
 * Class BeanInitConfig
 *
 * @since 2.0
 *
 * @Bean()
 */
class BeanInitConfig
{
    /**
     * @var string
     */
    private $configValue;

    /**
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function init(): void
    {
        $this->configValue = config('data');
    }

    /**
     * @return array
     */
    public function getConfigValue(): string
    {
        return $this->configValue;
    }
}