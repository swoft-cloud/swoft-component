<?php

namespace SwoftTest\Rpc\Client\Testing\Lib;

use Swoft\Core\ResultInterface;

/**
 * Interface DemoServiceInterface
 * @package SwoftTest\Db\Testing\Lib
 * @method ResultInterface deferVersion()
 */
interface DemoServiceInterface
{
    public function version();
}
