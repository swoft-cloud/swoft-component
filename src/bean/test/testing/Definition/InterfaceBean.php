<?php declare(strict_types=1);


namespace SwoftTest\Bean\Testing\Definition;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use SwoftTest\Bean\Testing\Contract\TestInterface;

/**
 * Class InterfaceBean
 *
 * @since 2.0
 *
 * @Bean()
 */
class InterfaceBean
{
    /**
     * @Inject()
     *
     * @var TestInterface
     */
    private $testInterface;

    /**
     * @Inject("interfaceTwo")
     *
     * @var TestInterface
     */
    private $testInterface2;

    /**
     * @Inject("interfaceOne")
     *
     * @var TestInterface
     */
    private $testInterface3;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->testInterface->getName();
    }

    /**
     * @return string
     */
    public function getName2(): string
    {
        return $this->testInterface2->getName();
    }

    /**
     * @return string
     */
    public function getName3(): string
    {
        return $this->testInterface3->getName();
    }
}