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

/**
 * Class CommaNameClass
 *
 * @since 2.0
 *
 * @Bean("commaNameClass")
 */
class CommaNameClass
{
    /**
     * @var ManyInstance
     */
    private $manyInstance2;

    /**
     * @return ManyInstance
     */
    public function getManyInstance2(): ManyInstance
    {
        return $this->manyInstance2;
    }
}
