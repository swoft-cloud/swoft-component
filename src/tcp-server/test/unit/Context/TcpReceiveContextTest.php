<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Tcp\Server\Unit\Context;

use PHPUnit\Framework\TestCase;
use Swoft\Tcp\Server\Context\TcpReceiveContext;
use Swoft\Tcp\Server\Request;
use SwoftTest\Tcp\Server\Testing\MockTcpResponse;

/**
 * Class TcpReceiveContextTest
 */
class TcpReceiveContextTest extends TestCase
{
    public function testBasic(): void
    {
        $req = Request::new(2, 'data', 3);
        $res = new MockTcpResponse();
        $res->setFd(2);
        $res->setContent('CONTENT');

        $ctx = TcpReceiveContext::new(2, $req, $res);

        $this->assertSame(2, $ctx->getFd());
        $this->assertSame(3, $ctx->getRequest()->getReactorId());
        $this->assertSame('data', $ctx->getRequest()->getRawData());

        $this->assertSame(2, $ctx->getResponse()->getFd());
        $this->assertSame('CONTENT', $ctx->getResponse()->getContent());

        $ctx->clear();
        $this->assertSame(-1, $ctx->getFd());

        $ctx = new TcpReceiveContext();
        $this->assertSame(-1, $ctx->getFd());

        $ctx->setRequest($req);
        $this->assertSame(2, $ctx->getFd());
        $this->assertSame(3, $ctx->getRequest()->getReactorId());

        $ctx->setResponse($res);
        $this->assertSame(2, $ctx->getResponse()->getFd());
        $this->assertSame('CONTENT', $ctx->getResponse()->getContent());
    }
}
