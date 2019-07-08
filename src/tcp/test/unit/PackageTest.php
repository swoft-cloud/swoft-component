<?php declare(strict_types=1);

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
        $this->assertEmpty($pkg->getData());
        $this->assertEmpty($pkg->getDataString());
    }
}
