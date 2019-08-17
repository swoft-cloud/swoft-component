<?php declare(strict_types=1);

namespace SwoftTest\Stdlib\Unit\Helper;

use PHPUnit\Framework\TestCase;
use Swoft\Stdlib\Helper\Dir;

/**
 * Class JsonHelperTest
 *
 * @since 2.0
 */
class DirectoryHelperTest extends TestCase
{
    /**
     * Test unit of Create directory
     * CarpCai <2019-06-27 13:20>
     */
    public function testMake(): void
    {
        $dir = __DIR__ . '/directory_test/test_make_dir';
        Dir::make($dir);
        $this->assertTrue(file_exists($dir) && is_dir($dir) && (@opendir($dir)));

        $dir = __DIR__ . '/directory_test/test_make_dir_mode';
        Dir::make($dir, 111);
        $this->assertTrue(file_exists($dir) && is_dir($dir) && (@opendir($dir) === false));
    }
}
