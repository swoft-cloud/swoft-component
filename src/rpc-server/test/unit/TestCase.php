<?php declare(strict_types=1);


namespace SwoftTest\Rpc\Server\Unit;

use Swoft\Test\Rpc\MockRpcServer;

/**
 * Class TestCase
 *
 * @since 2.0
 */
abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var MockRpcServer
     */
    protected $mockRpcServer;

    /**
     * Set up
     */
    public function setUp()
    {
        $this->mockRpcServer = new MockRpcServer();
    }
}