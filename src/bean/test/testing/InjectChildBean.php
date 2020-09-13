<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Bean\Testing;

use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class InjectChildBean
 *
 * @since 2.0
 *
 * @Bean()
 */
class InjectChildBean
{
    /**
     * @return string
     */
    public function getData(): string
    {
        return 'InjectChildBeanData';
    }
}
