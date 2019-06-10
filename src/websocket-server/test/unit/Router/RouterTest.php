<?php declare(strict_types=1);

namespace SwoftTest\WebSocket\Server\Unit\Router;

use PHPUnit\Framework\TestCase;
use ReflectionException;
use Swoft\Bean\Exception\ContainerException;
use Swoft\WebSocket\Server\Router\Router;
use SwoftTest\Testing\Concern\CommonTestAssertTrait;
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
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function testRouter(): void
    {
        /** @var Router $router */
        $router = bean('wsRouter');

        $this->assertFalse($router->isEnableDynamicRoute());
        $this->assertTrue($router->hasModule('/ws-test/chat'));
        $this->assertGreaterThan(0, $router->getCounter());
        $this->assertGreaterThan(0, $router->getModuleCount());

        $this->assertNotEmpty($router->getModules());
        $this->assertNotEmpty($router->getCommands());
    }

    /**
     * @throws ReflectionException
     * @throws ContainerException
     */
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

        // not enable
        $this->assertFalse($router->isEnableDynamicRoute());
        $router->addModule('/users/{id}', ['name' => 'test']);

        $info = $router->match('/users/12');
        $this->assertEmpty($info);

        $info = $router->match('/users/{id}');
        $this->assertNotEmpty($info);
        $this->assertSame('/users/{id}', $info['path']);

        // open dynamic route
        $router->setEnableDynamicRoute(true);

        $info = $router->match('/users/12');
        $this->assertNotEmpty($info);
        $this->assertArrayHasKey('routeParams', $info);
        $this->assertArrayHasKey('id', $info['routeParams']);
        $this->assertNotEmpty($info['routeParams']['id']);
        $this->assertSame('12', $info['routeParams']['id']);

        $this->assertTrue($router->isEnableDynamicRoute());
        $router->setEnableDynamicRoute(false);
    }
}
