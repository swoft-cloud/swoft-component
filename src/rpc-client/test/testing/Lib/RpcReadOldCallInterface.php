<?php declare(strict_types=1);

namespace SwoftTest\Rpc\Client\Testing\Lib;


interface RpcReadOldCallInterface
{
    /**
     * @return int
     */
    public function getIntResult();

    /**
     * @return string
     */
    public function getStringResult();
}
