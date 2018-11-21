<?php
namespace SwoftTest\Rpc\Testing\Services;

use SwoftTest\Rpc\Testing\Lib\DemoServiceInterface;
use Swoft\Rpc\Server\Bean\Annotation\Service;
use Swoft\Core\ResultInterface;

/**
 * Class DemoService
 * @Service()
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