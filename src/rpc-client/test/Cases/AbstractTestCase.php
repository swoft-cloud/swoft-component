<?php

namespace SwoftTest\Rpc\Client;

use PHPUnit\Framework\TestCase;

/**
 * @uses      AbstractTestCase
 * @version   2017年11月03日
 * @author    huangzhhui <huangzhwork@gmail.com>
 * @copyright Copyright 2010-2017 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class AbstractTestCase extends TestCase
{
    protected function tearDown()
    {
        parent::tearDown();
        swoole_timer_after(10 * 1000, function () {
            swoole_event_exit();
        });
    }
}