<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
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
