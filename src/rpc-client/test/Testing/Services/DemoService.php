<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace SwoftTest\RpcClient\Testing\Services;

use Swoft\Core\ResultInterface;
use Swoft\Rpc\Server\Bean\Annotation\Service;
use SwoftTest\RpcClient\Testing\Lib\DemoServiceInterface;

/**
 * Class DemoService
 * @Service
 * @method ResultInterface deferVersion()
 * @method ResultInterface deferLongMessage($string)
 * @method ResultInterface deferGet($id)
 * @package App\Services
 */
class DemoService implements DemoServiceInterface
{
    public function version()
    {
        return '1.0.0';
    }

    public function longMessage($string)
    {
        $res = '';
        for ($i = 0; $i < 50000; $i++) {
            $res .= $string;
        }
        return $res;
    }

    public function get($id)
    {
        \co::sleep(2);
        return $id;
    }
}
