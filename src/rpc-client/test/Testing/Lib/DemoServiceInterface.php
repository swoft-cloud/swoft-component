<?php

namespace SwoftTest\Rpc\Testing\Lib;

use Swoft\Core\ResultInterface;

/**
 * Interface DemoServiceInterface
 * @method ResultInterface deferVersion()
 * @method ResultInterface deferLongMessage($string)
 */
interface DemoServiceInterface
{
    public function version();

    public function longMessage($string);
}
