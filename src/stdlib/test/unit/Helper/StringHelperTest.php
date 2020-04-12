<?php declare(strict_types=1);

namespace SwoftTest\Stdlib\Unit\Helper;

use Exception;
use PHPUnit\Framework\TestCase;
use Swoft\Stdlib\Helper\Str;
use const STR_PAD_LEFT;

/**
 * Class StringHelperTest
 *
 * @since 2.0
 */
class StringHelperTest extends TestCase
{
    public function testGetClassName(): void
    {
        $name = Str::getClassName('App\Http\Controller\UserController', 'Controller');
        $this->assertSame('user', $name);

        $name = Str::getClassName('App\Http\Controller\UserController', '');
        $this->assertSame('App\Http\Controller\UserController', $name);
    }

    public function testFormatPath(): void
    {
        $this->assertSame('/', Str::formatPath(''));
        $this->assertSame('/a', Str::formatPath('a'));
        $this->assertSame('/a', Str::formatPath('a/'));
        $this->assertSame('/a', Str::formatPath('/a/'));
    }

    public function testRmPharPrefix(): void
    {
        $path = Str::rmPharPrefix('phar:///path/to/some.phar/vendor/composer');
        $this->assertSame('/path/to/vendor/composer', $path);

        $path = Str::rmPharPrefix('phar:///path/to/some.phar/vendor/composer/');
        $this->assertSame('/path/to/vendor/composer/', $path);

        $path = Str::rmPharPrefix('phar:///path/to/some.phar/vendor/composer/.env');
        $this->assertSame('/path/to/vendor/composer/.env', $path);

        $path = Str::rmPharPrefix('/vendor/composer/.env');
        $this->assertSame('/vendor/composer/.env', $path);
    }

    /**
     * @throws Exception
     */
    public function testGetUnique(): void
    {
        $uniqueId = Str::getUniqid();

        $this->assertNotEmpty($uniqueId);
        $this->assertIsString($uniqueId);

        $uniqueId = Str::uniqIdReal();
        $this->assertNotEmpty($uniqueId);

        $uniqueId = Str::microTimeId();
        $this->assertNotEmpty($uniqueId);
    }

    public function testPad(): void
    {
        $this->assertSame('ABC   ', Str::pad('ABC', 6));
        $this->assertSame('   ABC', Str::pad('ABC', 6, ' ', STR_PAD_LEFT));

        $this->assertSame('123   ', Str::pad(123, 6));
    }
}
