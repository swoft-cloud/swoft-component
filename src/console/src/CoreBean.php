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

namespace Swoft\Console;

use Swoft\Bean\Annotation\BootBean;
use Swoft\Console\Router\HandlerMapping;
use Swoft\Core\BootBeanInterface;

/**
 * The core bean of console
 *
 * @BootBean(server=true)
 */
class CoreBean implements BootBeanInterface
{
    /**
     * @return array
     */
    public function beans(): array
    {
        return [
            'commandRoute' => [
                'class' => HandlerMapping::class,
            ],
        ];
    }
}
