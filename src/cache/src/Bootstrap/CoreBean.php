<?php
declare(strict_types=1);

/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Cache\Bootstrap;

use Swoft\Bean\Annotation\BootBean;
use Swoft\Cache\Cache;
use Swoft\Core\BootBeanInterface;

/**
 * The core bean of cache
 *
 * @BootBean
 */
class CoreBean implements BootBeanInterface
{
    /**
     * @return array
     */
    public function beans(): array
    {
        return [
            'cache' => [
                'class' => Cache::class,
            ]
        ];
    }
}
