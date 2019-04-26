<?php declare(strict_types=1);


namespace SwoftTest\Component\Testing\Config;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Config\Annotation\Mapping\Config;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;

/**
 * Class DemoConfig
 *
 * @since 2.0
 *
 * @Bean(name="testDemoConfig", alias="testDemoConfigAlias")
 */
class DemoConfig
{
    /**
     * @Config("data")
     *
     * @var string
     */
    private $data = '';

    /**
     * @Config("array")
     *
     * @var array
     */
    private $array = [];

    /**
     * @Config("other.data")
     *
     * @var string
     */
    private $otherData = '';

    /**
     * @Config("other.array")
     *
     * @var array
     */
    private $otherArray = [];

    /**
     * @return string
     */
    public function getData(): string
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function getArray(): array
    {
        return $this->array;
    }

    /**
     * @return string
     */
    public function getOtherData(): string
    {
        return $this->otherData;
    }

    /**
     * @return array
     */
    public function getOtherArray(): array
    {
        return $this->otherArray;
    }
}