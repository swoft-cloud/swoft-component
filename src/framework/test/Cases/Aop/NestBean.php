<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Aop;

use Swoft\Bean\Annotation\Bean;
use Swoft\Bean\Annotation\None;

/**
 * Class NestBean
 *
 * @Bean
 */
class NestBean
{
    /**
     * @None
     */
    public function method1(): string
    {
        return $this->method2() . '.' . __FUNCTION__;
    }

    /**
     * @None
     */
    public function method2(): string
    {
        return __FUNCTION__;
    }

}
