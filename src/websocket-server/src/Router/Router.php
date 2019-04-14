<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\Router;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Contract\RouterInterface;
use Swoft\WebSocket\Server\Helper\WsHelper;

/**
 * Class Router
 * @package Swoft\WebSocket\Server\Router
 *
 * @Bean("wsRouter")
 */
class Router implements RouterInterface
{
    /**
     * @var array
     * [
     *  '/echo' => [
     *      'path'  => route path,
     *      'class' => moduleClass,
     *      'name'  => module name,
     *      'messageParser'  => message parser class,
     *      'defaultCommand' => default command,
     *      'eventMethods'   => [
     *          'handshake' => method1, (on the moduleClass)
     *          'open'      => method2,
     *          'close'     => method3,
     *      ],
     *  ],
     *  ... ...
     * ]
     */
    private $modules = [];

    /**
     * @var array
     * [
     *  '/echo' => [
     *      'prefix1.cmd1' => [controllerClass1, method1],
     *      'prefix1.cmd2' => [controllerClass1, method2],
     *      'prefix2.cmd1' => [controllerClass2, method1],
     *  ]
     * ]
     */
    private $commands = [];

    /**
     * Command counter
     * @var int
     */
    private $counter = 0;

    private $defaultRoute = '';
    private $defaultPrefix = '';
    private $defaultCommand = '';

    /**
     * @param string $path
     * @param array  $moduleInfo
     */
    public function addModule(string $path, array $moduleInfo = []): void
    {
        $path = WsHelper::formatPath($path);
        // add module
        $this->modules[$path] = $moduleInfo;
    }

    /**
     * @param string   $path
     * @param string   $cmdId
     * @param callable $handler
     */
    public function addCommand(string $path, string $cmdId, $handler): void
    {
        $path = WsHelper::formatPath($path);

        $this->counter++;
        $this->commands[$path][$cmdId] = $handler;
    }

    /**
     * Match route path for find module info
     *
     * @param string $path e.g '/echo'
     * @return array
     */
    public function match(string $path): array
    {
        $path = WsHelper::formatPath($path);

        return $this->modules[$path] ?? [];
    }

    /**
     * @param string $path
     * @param string $route like 'home.index'
     * @return array
     * [
     *  status,
     *  [controllerClass, method]
     * ]
     */
    public function matchCommand(string $path, string $route): array
    {
        $path = WsHelper::formatPath($path);
        if (!isset($this->commands[$path])) {
            return [self::NOT_FOUND, null];
        }

        $route = \trim($route) ?: $this->modules[$path]['defaultCommand'];

        if (isset($this->commands[$path][$route])) {
            return [self::FOUND, $this->commands[$path][$route]];
        }

        return [self::NOT_FOUND, null];
    }

    /**
     * @param string $path
     * @return bool
     */
    public function hasModule(string $path): bool
    {
        return isset($this->modules[$path]);
    }

    /**
     * @return array
     */
    public function getModules(): array
    {
        return $this->modules;
    }

    /**
     * @return array
     */
    public function getCommands(): array
    {
        return $this->commands;
    }

    /**
     * @return int
     */
    public function getModuleCount(): int
    {
        return \count($this->modules);
    }

    /**
     * @return int
     */
    public function getCounter(): int
    {
        return $this->counter;
    }
}
