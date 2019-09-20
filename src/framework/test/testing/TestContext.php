<?php declare(strict_types=1);


namespace SwoftTest\Testing;


use ReflectionException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Concern\PrototypeTrait;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Context\AbstractContext;

/**
 * Class TestContext
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::PROTOTYPE)
 */
class TestContext extends AbstractContext
{
    use PrototypeTrait;

    /**
     * @return TestContext
     */
    public static function new(): self
    {
        return self::__instance();
    }
}