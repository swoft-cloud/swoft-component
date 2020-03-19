<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Tcp\Unit;

use PHPUnit\Framework\TestCase;
use Swoft\Tcp\Response;

/**
 * Class ResponseTest
 */
class ResponseTest extends TestCase
{
    public function testBasic(): void
    {
        $resp = new Response();

        $this->assertSame(Response::OK, $resp->getCode());
        $this->assertSame(Response::DEFAULT_MSG, $resp->getMsg());
        $this->assertSame('', $resp->getContent());
        $this->assertSame([], $resp->getExt());
        $this->assertNull($resp->getData());
        $this->assertSame('', $resp->getDataString());
        $this->assertSame('{"code":0,"msg":"OK","data":null,"ext":[]}', (string)$resp);

        $resp->setCode(500);
        $resp->setMsg('error');
        $resp->setData('string data');
        $resp->setExt(['traceId' => 'id123']);
        $resp->setContent('content');

        $this->assertSame(500, $resp->getCode());
        $this->assertSame('error', $resp->getMsg());
        $this->assertSame('content', $resp->getContent());
        $this->assertSame(['traceId' => 'id123'], $resp->getExt());
        $this->assertSame('string data', $resp->getData());
        $this->assertSame('string data', $resp->getDataString());
        $this->assertSame('{"code":500,"msg":"error","data":"string data","ext":{"traceId":"id123"}}', (string)$resp);

        $resp->setData(['array data']);
        $this->assertSame(['array data'], $resp->getData());
        $this->assertSame('["array data"]', $resp->getDataString());
    }

    public function testEmpty(): void
    {
        $w = new Response();

        $this->assertTrue($w->isEmpty());

        $w->setContent('hi');
        $this->assertFalse($w->isEmpty());
        $w->setCode(23);
        $w->setContent('');
        $this->assertFalse($w->isEmpty());
        $w->setCode(0);
        $w->setExt(['a']);
        $w->setContent('');
        $this->assertFalse($w->isEmpty());

        $w->setCode(0);
        $w->setExt([]);
        $w->setData(['a']);
        $w->setContent('');
        $this->assertFalse($w->isEmpty());
    }
}
