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
     *      'events'   => [
     *          'handShake' => method1, (on the moduleClass)
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
     * @param string $path
     * @param array  $moduleInfo
     */
    public function addModule(string $path, array $moduleInfo = []): void
    {
        $path = WsHelper::formatPath($path);
        // add
        $this->modules[$path] = $moduleInfo;
    }

    /**
     * @param string $path
     * @param array  $commands
     */
    public function addCommands(string $path, array $commands): void
    {
        $path = WsHelper::formatPath($path);

        if (isset($this->commands[$path])) {
            $this->commands[$path] = \array_merge($this->commands[$path], $commands);
        } else {
            $this->commands[$path] = $commands;
        }
    }

    /**
     * @param string   $path
     * @param string   $commandId
     * @param callable $handler
     */
    public function addCommand(string $path, string $commandId, $handler): void
    {
        $path = WsHelper::formatPath($path);
        // add
        $this->commands[$path][$commandId] = $handler;
    }

    /**
     * Match route path for find module info
     *
     * @param string $path e.g '/echo'
     * @return array
     * @throws \Swoft\WebSocket\Server\Exception\WsRouteException
     */
    public function match(string $path): array
    {
        $path = WsHelper::formatPath($path);

        return $this->modules[$path] ?? [];
    }

    /**
     * @param string $path
     * @param string $command
     * @return array
     */
    public function matchCommand(string $path, string $command): array
    {
        $path = WsHelper::formatPath($path);
        if (!isset($this->commands[$path])) {
            return [self::NOT_FOUND, null];
        }

        $command = \trim($command) ?: $this->modules[$path]['defaultCommand'];

        if (isset($this->commands[$path][$command])) {
            // $commands[$command] is: [controllerClass, method]
            return [self::FOUND, $this->commands[$path][$command]];
        }

        return [self::NOT_FOUND, null];
    }

    /**
     * @param string $path
     * @return bool
     */
    public function hasRoute(string $path): bool
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
}
