<?php

namespace SwoftTest\Defer\Cases;

use Swoft\Defer\Defer;


/**
 * @uses    DeferTest
 * @author  huangzhhui <huangzhwork@gmail.com>
 */
class DeferTest extends AbstractTestCase
{
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function testStack()
    {
        $defer = new Defer();
        ob_start();
        $defer->push(function () {
            echo 1;
        });
        $defer(function () use ($defer) {
            echo 3;
            $defer(function () {
                echo 2;
            });
        });
        $defer([$this, 'callMethod']);
        $defer([$this, 'noPermissionMethod']);
        $defer->run();
        $content = ob_get_clean();
        $this->assertEquals('4321', $content);
    }

    public function callMethod()
    {
        echo 4;
    }

    protected function noPermissionMethod()
    {
        echo 5;
    }

}