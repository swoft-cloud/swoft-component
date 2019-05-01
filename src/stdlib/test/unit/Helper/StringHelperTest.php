<?php declare(strict_types=1);

namespace SwoftTest\Stdlib\Helper;

use PHPUnit\Framework\TestCase;
use Swoft\Stdlib\Helper\Str;

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

    public function testRmPharPrefix(): void
    {
        $path = Str::rmPharPrefix('phar:///path/to/some.phar/vendor/composer');
        $this->assertSame('/vendor/composer', $path);
    }
}
