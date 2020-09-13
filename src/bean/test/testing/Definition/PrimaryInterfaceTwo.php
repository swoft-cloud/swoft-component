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
use Swoft\Bean\Annotation\Mapping\Primary;
use SwoftTest\Bean\Testing\Contract\PrimaryInterface;

/**
 * Class PrimaryInterfaceTwo
 *
 * @since 2.0
 *
 * @Bean()
 * @Primary()
 */
class PrimaryInterfaceTwo implements PrimaryInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'PrimaryInterfaceTwo';
    }
}
