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
use Swoft\Http\Message\Uri\Uri;

/**
 * Class UriTest
 */
class UriTest extends TestCase
{
    /**
     */
    public function testBasic(): void
    {
        $uri = Uri::new('/home/index.html');
        $this->assertNotEmpty($uri);
        $this->assertSame('http', $uri->getScheme());
        $this->assertSame('/home/index.html', $uri->getPath());
        $this->assertSame('', $uri->getAuthority());

        $uri = Uri::new('', [
            'path' => '/home/index'
        ]);

        $this->assertNotEmpty($uri);
    }

    /**
     */
    public function testIssue792(): void
    {
        $uri = Uri::new('http://ic.clive.domain.com/db');

        $this->assertSame('ic.clive.domain.com', $uri->getHost());

        $this->assertNull($uri->getPort());
        $this->assertSame('ic.clive.domain.com', $uri->getHost());
        $this->assertSame('http', $uri->getScheme());
        $this->assertSame('/db', $uri->getPath());
        $this->assertSame('ic.clive.domain.com', $uri->getAuthority());
    }
}
