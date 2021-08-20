<?php declare(strict_types=1);

namespace SwoftTest\Rpc\Client\Testing;

use Swoole\Coroutine;
use Swoft\Rpc\Protocol;
use Swoft\Rpc\Server\Annotation\Mapping\Service;
use SwoftTest\Rpc\Client\Testing\Lib\RpcReadOldCallInterface;

/**
 * Class RpcReadOldCallService
 *
 * @since 2.0
 *
 * @Service(version=Protocol::DEFAULT_VERSION)
 */
class RpcReadOldCallService implements RpcReadOldCallInterface
{

    /**
     * @return int
     */
    public function getIntResult()
    {
        return 1;
    }

    /**
     * @return string
     */
    public function getStringResult()
    {
        Coroutine::sleep(1);
        return 'string';
    }
}
