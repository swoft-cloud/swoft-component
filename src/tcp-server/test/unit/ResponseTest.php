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

use PHPUnit\Framework\TestCase;
use Swoft\Tcp\Server\Response;
use SwoftTest\Tcp\Server\Testing\MockTcpResponse;

/**
 * Class ResponseTest
 */
class ResponseTest extends TestCase
{
    public function testBasic(): void
    {
        $w = Response::new(22);

        $this->assertFalse($w->isSent());
        $this->assertFalse($w->isFail());
        $this->assertTrue($w->isOK());
        $this->assertSame(22, $w->getFd());
        $this->assertSame(22, $w->getReqFd());

        $w->setFd(12);
        $this->assertSame(12, $w->getFd());

        $w->setSent(true);
        $this->assertTrue($w->isSent());

        $this->assertTrue($w->isEmpty());
        $w->setContent('hi');
        $this->assertSame('hi', $w->getContent());
        $this->assertFalse($w->isEmpty());

        $w->setCode(23);
        $w->setContent('');
        $this->assertFalse($w->isOK());
        $this->assertSame(23, $w->getCode());
        $this->assertFalse($w->isEmpty());

        $this->assertSame('OK', $w->getMsg());
        $w->setMsg('Fail');
        $this->assertSame('Fail', $w->getMsg());

        $this->assertNotEmpty($arr = $w->toArray());
        $this->assertSame(23, $arr['code']);
        $this->assertSame('Fail', $arr['msg']);

        $this->assertSame('{"code":23,"msg":"Fail","data":null,"ext":[]}', (string)$w);
    }

    // public function testSend(): void
    // {
    //     $w = new MockTcpResponse();
    //     $w->send();
    // }
}
