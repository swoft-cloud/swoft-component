<?php declare(strict_types=1);

namespace SwoftTest\Tcp\Server\Unit;

use ReflectionException;
use Swoft\Tcp\Server\Exception\CommandNotFoundException;
use Swoft\Tcp\Server\Exception\TcpMiddlewareException;
use Swoft\Tcp\Server\Exception\TcpUnpackingException;
use Swoft\Tcp\Server\Request;
use SwoftTest\Tcp\Server\Testing\MockTcpResponse;
use Throwable;
use function bean;
use function get_class;

/**
 * Class TcpDispatcherTest
 */
class TcpDispatcherTest extends TcpServerTestCase
{
    /**
     * @throws ReflectionException
     * @throws CommandNotFoundException
     * @throws TcpMiddlewareException
     * @throws TcpUnpackingException
     */
    public function testDispatch(): void
    {
        $td = bean('tcpDispatcher');

        try {
            $req = Request::new(1, 'not-exist', 2);
            $res = new MockTcpResponse();

            $td->dispatch($req, $res);
        } catch (Throwable $e) {
            $this->assertSame(CommandNotFoundException::class, get_class($e));
            $this->assertSame("request command 'not-exist' is not found of the tcp server", $e->getMessage());
        }
    }
}
