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

namespace Swoft\Sg\Bootstrap;

use Swoft\Bean\Annotation\BootBean;
use Swoft\Core\BootBeanInterface;
use Swoft\Sg\BalancerSelector;
use Swoft\Sg\ProviderSelector;

/**
 *  The core bean of view
 *
 * @BootBean
 */
class CoreBean implements BootBeanInterface
{
    /**
     * @return array
     */
    public function beans():array
    {
        return [
            'balancerSelector' => [
                'class' => BalancerSelector::class,
            ],
            'providerSelector' => [
                'class' => ProviderSelector::class,
            ],
        ];
    }
}
