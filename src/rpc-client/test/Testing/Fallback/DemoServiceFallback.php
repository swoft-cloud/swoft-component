<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace SwoftTest\RpcClient\Testing\Fallback;

use Swoft\Core\ResultInterface;
use Swoft\Sg\Bean\Annotation\Fallback;
use SwoftTest\RpcClient\Testing\Lib\DemoServiceInterface;

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
