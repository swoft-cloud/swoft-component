<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\Router;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Contract\RouterInterface;
use Swoft\WebSocket\Server\Helper\WsHelper;
use function array_shift;
use function count;
use function preg_match;
use function preg_match_all;
use function strpos;
use function strtr;
use function trim;

/**
 * Class Router
 *
 * @package Swoft\WebSocket\Server\Router
 *
 * @Bean("wsRouter")
 */
class Router implements RouterInterface
{
    // Default var regex
    public const DEFAULT_REGEX = '[^/]+';

    /**
     * Command counter
     *
     * @var int
     */
    private $counter = 0;

    /**
     * @var array
     * [
     *  '/echo' => [
     *      'path'   => route path,
     *      'class'  => moduleClass,
     *      'name'   => module name,
     *      'params' => ['id' => '\d+'],
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
     * @var bool Enable dynamic route
     */
    private $enableDynamicRoute = false;

    /**
     * @param string $path
     * @param array  $info module Info
     */
    public function addModule(string $path, array $info = []): void
    {
        $path = WsHelper::formatPath($path);
        // Re-set path
        $info['path'] = $path;

        // Exist path var. eg: "/users/{id}"
        if (!$this->enableDynamicRoute || strpos($path, '{') === false) {
            $info['regex'] = '';

            // Add module
            $this->modules[$path] = $info;
        }

        $params = $info['params'] ?? [];

        // Parse the parameters and replace them with the corresponding regular
        if (preg_match_all('#\{([a-zA-Z_][\w-]*)\}#', $path, $m)) {
            /** @var array[] $m */
            $pairs = [];

            foreach ($m[1] as $name) {
                $regex = $params[$name] ?? self::DEFAULT_REGEX;
                // Build pairs
                $pairs['{' . $name . '}'] = '(' . $regex . ')';
            }

            $info['vars']  = $m[1];
            $info['regex'] = '#^' . strtr($path, $pairs) . '$#';
        }

        // Add module
        $this->modules[$path] = $info;
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
     *
     * @return array
     */
    public function match(string $path): array
    {
        $path = WsHelper::formatPath($path);

        if (isset($this->modules[$path])) {
            return $this->modules[$path];
        }

        // Not enable dynamic route
        if (!$this->enableDynamicRoute) {
            return [];
        }

        // If is dynamic route
        foreach ($this->modules as $module) {
            if (!$pathRegex = $module['regex']) {
                continue;
            }

            // Regex match
            if (preg_match($pathRegex, $path, $matches)) {
                $params   = [];
                $pathVars = $module['vars'];

                // First is full match.
                array_shift($matches);
                foreach ($matches as $index => $value) {
                    $params[$pathVars[$index]] = $value;
                }

                $module['routeParams'] = $params;
                return $module;
            }
        }

        return [];
    }

    /**
     * @param string $path
     * @param string $route like 'home.index'
     *
     * @return array
     *                      [
     *                          status,
     *                          [controllerClass, method]
     *                      ]
     */
    public function matchCommand(string $path, string $route): array
    {
        $path = WsHelper::formatPath($path);
        if (!isset($this->commands[$path])) {
            return [self::NOT_FOUND, null];
        }

        $route = trim($route) ?: $this->modules[$path]['defaultCommand'];

        if (isset($this->commands[$path][$route])) {
            return [self::FOUND, $this->commands[$path][$route]];
        }

        return [self::NOT_FOUND, null];
    }

    /**
     * @param string $path
     *
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
        return count($this->modules);
    }

    /**
     * @return int
     */
    public function getCounter(): int
    {
        return $this->counter;
    }

    /**
     * @return bool
     */
    public function isEnableDynamicRoute(): bool
    {
        return $this->enableDynamicRoute;
    }

    /**
     * @param bool $enable
     */
    public function setEnableDynamicRoute(bool $enable): void
    {
        $this->enableDynamicRoute = $enable;
    }
}
