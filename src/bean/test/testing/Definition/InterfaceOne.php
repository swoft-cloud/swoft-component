<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Bean\Testing\Definition;

use Swoft\Bean\Annotation\Mapping\Bean;
use SwoftTest\Bean\Testing\Contract\TestInterface;

/**
 * Class InterfaceOne
 *
 * @since 2.0
 *
 * @Bean("interfaceOne")
 */
class InterfaceOne implements TestInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'InterfaceOne';
    }
}
