<?php declare(strict_types=1);


namespace SwoftTest\Component\Testing\Aop;

use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class ParamsAop
 *
 * @since 2.0
 *
 * @Bean()
 */
class ParamsAop
{
    public function method(string $name, int $count, int $type = 2, int $max = null):string
    {
        return 'method';
    }
}