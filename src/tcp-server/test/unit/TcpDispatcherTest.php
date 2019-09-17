<?php declare(strict_types=1);

namespace SwoftTest\Tcp\Server\Unit;

use function bean;
use Swoft\Tcp\Server\Request;
use SwoftTest\Tcp\Server\Testing\MockTcpResponse;

/**
 * Class TcpDispatcherTest
 */
class TcpDispatcherTest extends TcpServerTestCase
{
    /**
     */
    public function testDispatch(): void
    {
        $td = bean('tcpDispatcher');

        $req = Request::new(1, 'data', 2);
        $res = new MockTcpResponse();

        $this->assertNotEmpty($td);
        // $td->dispatch($req, $res);
    }
}
