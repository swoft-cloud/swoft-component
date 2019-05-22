<?php declare(strict_types=1);


namespace SwoftTest\Component\Testing\Aop;

use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class DemoAop
 *
 * @since 2.0
 *
 * @Bean("testDemoAop")
 */
class DemoAop
{
    /**
     * @return string
     */
    public function method(): string
    {
        return 'doMethod';
    }

    /**
     * @throws \Exception
     */
    public function ex()
    {
        throw new \Exception('exception message');
    }
}