<?php declare(strict_types=1);


namespace SwoftTest\Component\Testing\Aop;

use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class ZeroAop
 *
 * @since 2.0
 *
 * @Bean()
 */
class ZeroAop
{
    /**
     * @return int
     */
    public function returnZero(): int
    {
        return 0;
    }

    /**
     * @return int
     */
    public function afterZero(): int
    {
        return 1;
    }
}