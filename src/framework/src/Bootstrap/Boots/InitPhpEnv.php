<?php

namespace Swoft\Bootstrap\Boots;

use Swoft\Bean\Annotation\Bootstrap;

/**
 * @Bootstrap(order=1)
 * @author    huangzhhui <huangzhwork@gmail.com>
 */
class InitMbFunsEncoding implements Bootable
{
    /**
     * bootstrap
     */
    public function bootstrap()
    {
        \mb_internal_encoding('UTF-8');
        \date_default_timezone_set('Asia/Shanghai');
    }
}
