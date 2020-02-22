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
use Swoft\Tcp\Package;

/**
 * Class PackageTest
 */
class PackageTest extends TestCase
{
    public function testBasic(): void
    {
        $pkg = new Package();

        $this->assertEmpty($pkg->getCmd());
        $this->assertEmpty($pkg->getExt());
        $this->assertEmpty($pkg->getData());
        $this->assertEmpty($pkg->getDataString());
        $this->assertSame('{"cmd":"","data":null,"ext":[]}', $pkg->toString());

        $pkg->setCmd('test');
        $pkg->setExt(['id' => 23]);
        $pkg->setData('data');

        $this->assertSame('test', $pkg->getCmd());
        $this->assertSame('data', $pkg->getData());
        $this->assertSame('data', $pkg->getDataString());
        $this->assertSame(['id' => 23], $pkg->getExt());
        $this->assertSame('data', $pkg->toArray()['data']);
        $this->assertSame('{"cmd":"test","data":"data","ext":{"id":23}}', $pkg->toString());
    }
}
