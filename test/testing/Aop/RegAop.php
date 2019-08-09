<?php declare(strict_types=1);


namespace SwoftTest\Component\Testing\Aop;

use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class RegAop
 *
 * @since 2.0
 *
 * @Bean()
 */
class RegAop
{
    /**
     * @return string
     */
    public function method(): string
    {
        return 'method';
    }
}