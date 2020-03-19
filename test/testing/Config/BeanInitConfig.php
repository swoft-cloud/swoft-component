<?php declare(strict_types=1);

namespace SwoftTest\Component\Testing\Config;

use Swoft\Bean\Annotation\Mapping\Bean;

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

    public function init(): void
    {
        $this->configValue = config('data');
    }

    /**
     * @return string
     */
    public function getConfigValue(): string
    {
        return $this->configValue;
    }
}
