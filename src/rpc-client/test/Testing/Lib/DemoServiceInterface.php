<?php

namespace SwoftTest\Rpc\Testing\Lib;

use Swoft\Core\ResultInterface;

/**
 * Interface DemoServiceInterface
 * @method ResultInterface deferVersion()
 */
interface DemoServiceInterface
{
    public function version();
}
