<?php declare(strict_types=1);

namespace SwoftTest\Stdlib\Unit\Helper;

use function foo\func;
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
        $dir = '/tmp/test/directory_test/test_make_file';
        Dir::make($dir, 777);
        $this->assertSame(true, file_exists($dir) && is_dir($dir) && (@opendir($dir)));

        $dir = '/tmp/test/directory_test/test_make_file_mode';
        Dir::make($dir, 111);
        $this->assertSame(true, file_exists($dir) && is_dir($dir) && (@opendir($dir) === false));
    }
}
