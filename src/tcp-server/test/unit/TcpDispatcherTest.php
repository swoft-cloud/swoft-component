<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Tcp\Server\Unit;

use Swoft\Tcp\Server\Exception\CommandNotFoundException;
use Swoft\Tcp\Server\Request;
use Swoft\Tcp\Server\TcpDispatcher;
use Swoft\Tcp\Server\TcpServerBean;
use SwoftTest\Tcp\Server\Testing\Controller\TcpTestController;
use SwoftTest\Tcp\Server\Testing\Middleware\Global1Middleware;
use SwoftTest\Tcp\Server\Testing\MockTcpResponse;
use SwoftTest\Testing\TempArray;
use Throwable;
use function bean;
use function get_class;

/**
 * Class TcpDispatcherTest
 */
class TcpDispatcherTest extends TcpServerTestCase
{
    public function testSetting(): void
    {
        $td = new TcpDispatcher();
        $this->assertTrue($td->isEnable());
        $this->assertTrue($td->isPreCheckRoute());

        $td->setEnable(false);
        $this->assertFalse($td->isEnable());

        $td->setPreCheckRoute(false);
        $this->assertFalse($td->isPreCheckRoute());
    }

    public function testDispatch(): void
    {
        $td = bean(TcpServerBean::DISPATCHER);
        $this->assertTrue($td->isEnable());

        TempArray::reset();

        // token-text proto:
        // COMMAND BODY
        $ctx = $this->newTcpReceiveContext($fd = 1, 'tcpTest.index BODY');
        $res = $td->dispatch($ctx->getRequest(), $ctx->getResponse());

        $content = $res->getContent();
        $this->assertStringContainsString('>global', $content);
        $this->assertStringContainsString('>user1', $content);
        $this->assertStringContainsString('[INDEX]', $content);
        $this->assertStringContainsString('user1>', $content);
        $this->assertStringContainsString('global>', $content);

        // reset temp data
        $ret = TempArray::reset();
        $this->assertNotEmpty($ret);

        $key = TcpTestController::class . '::index';
        $this->assertArrayHasKey($key, $ret);
        $this->assertSame('hello', $ret[$key]);
    }

    public function testMiddlewares(): void
    {
        $td = bean(TcpServerBean::DISPATCHER);

        $oldMds = $td->getMiddlewares();
        $this->assertNotEmpty($oldMds);
        $this->assertArrayHasKey('global1test', $oldMds);
        $this->assertArrayNotHasKey('global1test1', $oldMds);

        $td->addMiddleware(Global1Middleware::class, 'global1test1');
        $td->addMiddlewares(['global1test2' => Global1Middleware::class]);
        $allMds = $td->getMiddlewares();
        $this->assertArrayHasKey('global1test', $allMds);
        $this->assertArrayHasKey('global1test1', $allMds);
        $this->assertArrayHasKey('global1test2', $allMds);

        // reset
        $td->setMiddlewares($oldMds);

        // pre and after
        $td = new TcpDispatcher();
        $this->assertEmpty($td->getPreMiddlewares());
        $td->setPreMiddlewares(['global1test1' => Global1Middleware::class]);
        $this->assertNotEmpty($preMds = $td->getPreMiddlewares());
        $this->assertArrayHasKey('global1test1', $preMds);

        $this->assertEmpty($td->getAfterMiddlewares());
        $td->setAfterMiddlewares(['global1test2' => Global1Middleware::class]);
        $this->assertNotEmpty($aftMds = $td->getAfterMiddlewares());
        $this->assertArrayHasKey('global1test2', $aftMds);
    }

    public function testCommandNotFound(): void
    {
        $td = bean(TcpServerBean::DISPATCHER);

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
