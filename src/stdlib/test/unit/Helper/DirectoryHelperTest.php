<?php declare(strict_types=1);

namespace SwoftTest\Stdlib\Unit\Helper;

use PHPUnit\Framework\TestCase;
use Swoft\Stdlib\Helper\Dir;
use function rmdir;

/**
 * Class JsonHelperTest
 *
 * @since 2.0
 */
class DirectoryHelperTest extends TestCase
{
    /**
     * Test unit of Create directory
     */
    public function testMake(): void
    {
        $base = __DIR__ . '/directory_test';
        $dir = $base . '/test_make_dir';
        Dir::make($dir);
        $this->assertTrue(file_exists($dir) && is_dir($dir) && (@opendir($dir)));
        @rmdir($dir);

        $dir = $base . '/test_make_dir_mode';
        Dir::make($dir, 111);
        $this->assertTrue(file_exists($dir) && is_dir($dir) && (@opendir($dir) === false));
        @rmdir($dir);
        @rmdir($base);
    }
}
