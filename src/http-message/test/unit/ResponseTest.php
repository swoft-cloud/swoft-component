<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace SwoftTest\Http\Message\Unit;

use PHPUnit\Framework\TestCase;
use Swoft\Http\Message\Cookie;
use Swoft\Http\Message\Response;

/**
 * Class ResponseTest
 *
 * @package SwoftTest\Http\Message\Unit
 */
class ResponseTest extends TestCase
{
    public function testBasic(): void
    {
        $w = new Response();
        $this->assertSame(200, $w->getStatusCode());
        $this->assertSame('', $w->getReasonPhrase());

        $w = $w->withStatus(302);
        $this->assertSame(302, $w->getStatusCode());
        $this->assertSame('Found', $w->getReasonPhrase());
    }

    public function testCookies(): void
    {
        $w = new Response();

        $this->assertEmpty($w->getCookies());

        $cookies = [
            'key1' => 'value1',
            'key2' => [
                'value' => 'value2',
            ],
            'key3' => [
                'value'    => 'value3',
                'httpOnly' => true
            ],
        ];

        $w->setCookies($cookies);

        $this->assertTrue($w->hasCookie('key1'));
        $this->assertFalse($w->hasCookie('not-exist'));

        $this->assertNotEmpty($ck1 = $w->getCookie('key1'));
        $this->assertSame('value1', $ck1['value']);
        $this->assertSame(0, $ck1['expires']);

        $this->assertNotEmpty($cks = $w->getCookies());
        $this->assertCount(3, $cks);

        $this->assertArrayHasKey('key1', $cks);
        $this->assertArrayHasKey('key2', $cks);
        $this->assertIsArray($cks['key1']);
        $this->assertSame('value1', $cks['key1']['value']);
        $this->assertSame('value2', $cks['key2']['value']);

        $w->delCookie('key1');
        $this->assertCount(3, $w->getCookies());
        $this->assertNotEmpty($ck1 = $w->getCookie('key1'));
        $this->assertSame(-60, $ck1['expires']);
        $this->assertSame('', $ck1['value']);

        $w->setCookies([]);
        $this->assertEmpty($w->getCookies());
    }

    public function testCookieObject(): void
    {
        $w = new Response();
        $c = (new Cookie())
            ->setValue('value1')
            ->setDomain('abc.com')
            ->setExpires(220)
            ->setHttpOnly(true);

        $this->assertTrue($c->isHttpOnly());
        $this->assertFalse($c->isHostOnly());
        $this->assertSame(220, $c->getExpires());
        $this->assertSame('value1', $c->getValue());
        $this->assertSame('abc.com', $c->getDomain());

        $w->setCookie('test', $c);
        $w->setCookie('test2', 'value2');

        $this->assertNotEmpty($cks = $w->getCookies());
        $this->assertArrayHasKey('test', $cks);
        $this->assertIsArray($cks['test']);
        $this->assertSame('value1', $cks['test']['value']);
        $this->assertSame('value2', $cks['test2']['value']);

        $w = $w->withoutCookies();
        $this->assertNotEmpty($cks = $w->getCookies());

        foreach ($cks as $item) {
            $this->assertSame('', $item['value']);
            $this->assertSame(-60, $item['expires']);
        }
    }
}
