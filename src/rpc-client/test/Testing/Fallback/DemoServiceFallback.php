<?php

namespace SwoftTest\Rpc\Testing\Fallback;


use Swoft\Sg\Bean\Annotation\Fallback;
use Swoft\Core\ResultInterface;
use SwoftTest\Rpc\Testing\Lib\DemoServiceInterface;

/**
 * Class DemoServiceFallback
 * @Fallback("demoFallback")
 * @method ResultInterface deferVersion
 * @method ResultInterface deferLongMessage($string)
 * @method ResultInterface deferGet($id)
 */
class DemoServiceFallback implements DemoServiceInterface
{
    public function version()
    {
        return 'versionFallback';
    }

    public function longMessage($string)
    {
        return 'bigMessageFallBack';
    }

    public function get($id)
    {
        return '';
    }
}
