<?php

namespace Swoft\Aop\Bootstrap;

use Swoft\Bean\Annotation\BootBean;
use Swoft\Core\BootBeanInterface;


/**
 * The corebean of swoft
 *
 * @BootBean()
 */
class CoreBean implements BootBeanInterface
{
    public function beans()
    {
        return [
        ];
    }
}