<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\WebSocket\Server\Unit\Router;

use PHPUnit\Framework\TestCase;
use Swoft\WebSocket\Server\Router\Router;
use SwoftTest\Testing\Concern\CommonTestAssertTrait;
use SwoftTest\WebSocket\Server\Testing\Middleware\User1Middleware;
use SwoftTest\WebSocket\Server\Testing\Middleware\User2Middleware;
use function bean;

/**
 * Class RouterTest
 *
 * @since 2.0
 */
class RouterTest extends TestCase
{
    use CommonTestAssertTrait;

    /**
     */
    public function testRouter(): void
    {
        /** @var Router $router */
        $router = bean('wsRouter');

        $this->assertTrue($router->hasModule('/ws-test/chat'));
        $this->assertGreaterThan(0, $router->getCounter());
        $this->assertGreaterThan(0, $router->getModuleCount());

        $this->assertNotEmpty($router->getModules());
        $this->assertNotEmpty($router->getCommands());

        [$status, $info] = $router->matchCommand('/ws-test/chat', 'chat.send');
        $this->assertSame(Router::FOUND, $status);
        $this->assertNotEmpty($info);
        $this->assertArrayHasKey('cmdId', $info);
        $this->assertArrayHasKey('opcode', $info);
        $this->assertArrayHasKey('handler', $info);
        $this->assertSame('chat.send', $info['cmdId']);
    }

    public function testAddModule(): void
    {
        /** @var Router $router */
        $router = bean('wsRouter');

        $this->assertFalse($router->hasModule('/new-test'));
        // add new module
        $router->addModule('/new-test', [
            'name' => 'test'
        ]);
        $this->assertTrue($router->hasModule('/ws-test/chat'));

        $info = $router->match('/new-test');
        $this->assertIsArray($info);
        $this->assertNotEmpty($info);
        $this->assertSame('test', $info['name']);
        $this->assertSame('/new-test', $info['path']);

        $info = $router->match('/not-exist');
        $this->assertEmpty($info);
        $this->assertIsArray($info);
    }

    public function testDynamicRoute(): void
    {
        /** @var Router $router */
        $router = new Router();
        $router->addModule('/page/{name}', ['name' => 'test']);

        $info = $router->match('/page/about');
        $this->assertNotEmpty($info);

        // limit by regex
        $router->addModule('/users/{id}', [
            'params' => [
                'id' => '\d+'
            ]
        ]);

        $info = $router->match('/users/12');
        $this->assertNotEmpty($info);
        $this->assertArrayHasKey('routeParams', $info);
        $this->assertArrayHasKey('id', $info['routeParams']);
        $this->assertNotEmpty($info['routeParams']['id']);
        $this->assertSame('12', $info['routeParams']['id']);

        $info = $router->match('/users/tom');
        $this->assertEmpty($info);
    }

    public function testDisableModule(): void
    {
        /** @var Router $router */
        $router = new Router();
        $router->setDisabledModules(['/chat']);

        $router->addModule('/chat', ['name' => 'test']);
        $this->assertSame(0, $router->getCounter());
        $this->assertEmpty($router->getModules());
        $this->assertEmpty($router->getCommands());

        $info = $router->match('/chat');
        $this->assertEmpty($info);
    }

    public function testMiddlewares(): void
    {
        /** @var Router $router */
        $router = bean('wsRouter');
        $fullId = '/ws-test/chat:chat.send';
        $fullId1 = '/ws-test/chat:chat.notify';

        $this->assertNotEmpty($ms = $router->getMiddlewares());
        $this->assertArrayHasKey($fullId, $ms);
        $this->assertArrayHasKey($fullId1, $ms);

        $this->assertEmpty($router->getMiddlewaresByID('not-exist'));

        $this->assertNotEmpty($ms = $router->getCmdMiddlewares('/ws-test/chat', 'chat.send'));
        $this->assertSame(User1Middleware::class, $ms[0]);
        $this->assertSame(User2Middleware::class, $ms[1]);

        $this->assertNotEmpty($ms = $router->getMiddlewaresByID($fullId1));
        $this->assertArrayContainValue($ms, User1Middleware::class);
        $this->assertArrayNotContainValue($ms, User2Middleware::class);
    }
}
