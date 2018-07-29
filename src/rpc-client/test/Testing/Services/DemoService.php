<?php
namespace SwoftTest\Rpc\Client\Testing\Services;

use SwoftTest\Rpc\Client\Testing\Lib\DemoServiceInterface;
use Swoft\Rpc\Server\Bean\Annotation\Service;
use Swoft\Core\ResultInterface;

/**
 * Class DemoService
 * @Service()
 * @method ResultInterface deferVersion()
 * @package App\Services
 */
class DemoService implements DemoServiceInterface
{
    public function version()
    {
        return '1.0.0';
    }
}