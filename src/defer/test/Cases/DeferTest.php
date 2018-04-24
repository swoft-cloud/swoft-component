<?php

namespace SwoftTest\Defer;

use Swoft\Defer\Defer;


/**
 * @uses    DeferTest
 * @author  huangzhhui <huangzhwork@gmail.com>
 */
class DeferTest extends AbstractTestCase
{

    /**
     * @test
     */
    public function stack()
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
        $content = ob_get_contents();
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