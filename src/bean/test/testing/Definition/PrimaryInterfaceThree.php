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
use SwoftTest\Bean\Testing\Contract\PrimaryInterface;

/**
 * Class PrimaryInterfaceThree
 *
 * @since 2.0
 *
 * @Bean()
 */
class PrimaryInterfaceThree implements PrimaryInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'PrimaryInterfaceThree';
    }
}
