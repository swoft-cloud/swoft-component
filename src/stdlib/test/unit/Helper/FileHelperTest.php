<?php declare(strict_types=1);

namespace SwoftTest\Stdlib\Unit\Helper;

use PHPUnit\Framework\TestCase;
use Swoft\Stdlib\Helper\FileHelper;

/**
 * Class FileHelperTest
 */
class FileHelperTest extends TestCase
{
    public function testGetSuffix(): void
    {
        $this->assertSame('', FileHelper::getSuffix('layout-php'));
        $this->assertSame('.php', FileHelper::getSuffix('layout.php'));
        $this->assertSame('php', FileHelper::getSuffix('layout.php', true));
    }

    public function testGetExtension(): void
    {
        $this->assertSame('', FileHelper::getExtension('layout-php'));
        $this->assertSame('.php', FileHelper::getExt('layout.php'));
        $this->assertSame('php', FileHelper::getExt('layout.php', true));
    }
}
