<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Stdlib\Unit\Helper;

use PHPUnit\Framework\TestCase;
use Swoft\Stdlib\Helper\FileHelper;

/**
 * Class FileHelperTest
 */
class FileHelperTest extends TestCase
{
    public function testConv2abs(): void
    {
        $rawPath = '/swoft-cli/vendor/composer/../../app/SwoftCLI.php';
        $absPath = FileHelper::conv2abs($rawPath);

        $this->assertSame('/swoft-cli/app/SwoftCLI.php', $absPath);

        $rawPath = 'swoft-cli/vendor/composer/../../app/SwoftCLI.php';
        $absPath = FileHelper::conv2abs($rawPath);

        $this->assertSame('swoft-cli/app/SwoftCLI.php', $absPath);
    }

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
