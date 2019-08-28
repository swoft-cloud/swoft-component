<?php declare(strict_types=1);

namespace SwoftTest\WebSocket\Server\Unit\Context;

use PHPUnit\Framework\TestCase;
use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;
use Swoft\WebSocket\Server\Context\WsCloseContext;
use Swoft\WebSocket\Server\Context\WsHandshakeContext;
use Swoft\WebSocket\Server\Context\WsOpenContext;
use SwoftTest\WebSocket\Server\Testing\MockHttpRequest;
use SwoftTest\WebSocket\Server\Testing\MockHttpResponse;

/**
 * Class WsContextTest
 */
class WsContextTest extends TestCase
{
    public function testHandshakeCtx(): void
    {
        $sr = MockHttpRequest::new([
            'request_uri' => '/an-path',
        ]);
        $pr = Request::new($sr);
        $sw = MockHttpResponse::new();
        $pw = Response::new($sw);

        $c = WsHandshakeContext::new($pr, $pw);
        $c->set('key', 'val');

        $this->assertSame('val', $c->get('key'));
        $this->assertSame($pr, $c->getRequest());
        $this->assertSame($pw, $c->getResponse());
        $this->assertSame('/an-path', $c->getRequest()->getUriPath());

        $c->clear();
        $this->assertNull($c->get('key'));
    }

    public function testOpenCtx(): void
    {
        $sr = MockHttpRequest::new([
            'request_uri' => '/an-path',
        ]);
        $pr = Request::new($sr);

        $c = WsOpenContext::new($pr);

        $this->assertSame($pr, $c->getRequest());
        $this->assertSame('/an-path', $c->getRequest()->getUriPath());
    }

    public function testCloseCtx(): void
    {
        $c = WsCloseContext::new(10, 11);

        $this->assertSame(10, $c->getFd());
        $this->assertSame(11, $c->getRid());
        $this->assertSame(11, $c->getReactorId());
    }
}
