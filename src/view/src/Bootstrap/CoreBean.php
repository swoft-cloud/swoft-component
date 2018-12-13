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

namespace Swoft\View\Bootstrap;

use Swoft\Bean\Annotation\BootBean;
use Swoft\Core\BootBeanInterface;
use Swoft\View\Base\View;

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
    public function beans(): array
    {
        return [
            'view'         => [
                'class'     => View::class,
            ],
        ];
    }
}
