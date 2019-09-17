<?php declare(strict_types=1);

namespace SwoftTest\Console\Unit;

use PHPUnit\Framework\TestCase;
use function bean;

/**
 * Class RouterTest
 */
class RouterTest extends TestCase
{
    public function testBasic(): void
    {
        $router = bean('cliRouter');
        $this->assertTrue($router->count() > 0);

        $router->setIdAliases(['run' => 'serve:run']);
        $this->assertNotEmpty($list = $router->getIdAliases());
        $this->assertSame('serve:run', $list['run']);
    }
}
