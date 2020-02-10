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
use Swoft\Tcp\Package;
use Swoft\Tcp\Server\Request;

/**
 * Class RequestTest
 */
class RequestTest extends TestCase
{
    public function testBasic(): void
    {
        $r = Request::new(1, 'data', 2);

        $this->assertSame(1, $r->getFd());
        $this->assertSame(2, $r->getReactorId());
        $this->assertSame('data', $r->getRawData());

        $pkg = Package::new('tcp.cmd', 'data', []);
        $r->setPackage($pkg);

        $this->assertSame('tcp.cmd', $r->getPackage()->getCmd());
    }
}
