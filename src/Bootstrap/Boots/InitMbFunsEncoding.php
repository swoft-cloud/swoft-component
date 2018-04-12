<?php

namespace Swoft\Bootstrap\Boots;

use Swoft\Bean\Annotation\Bootstrap;

/**
 * @Bootstrap(order=1)
 * @uses      InitMbFunsEncoding
 * @version   2017-11-02
 * @author    huangzhhui <huangzhwork@gmail.com>
 * @copyright Copyright 2010-2017 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class InitMbFunsEncoding implements Bootable
{
    /**
     * bootstrap
     */
    public function bootstrap()
    {
        mb_internal_encoding("UTF-8");
    }
}
