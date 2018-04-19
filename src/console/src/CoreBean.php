<?php

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
