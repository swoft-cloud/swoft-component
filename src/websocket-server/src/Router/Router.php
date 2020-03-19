<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\WebSocket\Server\Router;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Contract\RouterInterface;
use Swoft\Stdlib\Helper\Str;
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
 * @since 2.0
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
     * WebSocket modules
     *
     * [
     *  '/echo' => [
     *      'path'   => route path,
     *      'class'  => moduleClass,
     *      'name'   => module name,
     *      'params' => ['id' => '\d+'],
     *      'messageParser'  => message parser class,
     *      'defaultOpcode'  => 1,
     *      'defaultCommand' => default command,
     *      'eventMethods'   => [
     *          'handshake' => method1, (on the moduleClass)
     *          'open'      => method2,
     *          'close'     => method3,
     *      ],
     *  ],
     *  ... ...
     * ]
     *
     * @var array
     */
    private $modules = [];

    /**
     * Message commands for each module
     *
     * [
     *  '/echo' => [
     *      'prefix1.cmd1' => [
     *          'opcode'  => 0,
     *          'handler' => [controllerClass1, method1],
     *      ],
     *  ],
     * ]
     *
     * @var array
     */
    private $commands = [];

    /**
     * [
     *  '/ws-test:chat.send' => [middle1, middle2],
     * ]
     *
     * @var array
     */
    private $middlewares = [];

    /**
     * Want disabled modules
     *
     * [
     *  // path => 1,
     *  '/echo' => 1,
     * ]
     *
     * @var array
     */
    private $disabledModules = [];

    /**
     * @param string $path
     * @param array  $info module Info
     */
    public function addModule(string $path, array $info = []): void
    {
        $path = Str::formatPath($path);

        // It's an disabled module
        if (isset($this->disabledModules[$path])) {
            return;
        }

        // Re-set path
        $info['path']  = $path;
        $info['regex'] = '';

        // Not exist path var. eg: "/users/{id}"
        if (strpos($path, '{') === false) {
            $this->modules[$path] = $info;
            return;
        }

        $matches = [];
        $params  = $info['params'] ?? [];

        // Parse the parameters and replace them with the corresponding regular
        if (preg_match_all('#\{([a-zA-Z_][\w-]*)\}#', $path, $matches)) {
            /** @var array[] $m */
            $pairs = [];

            foreach ($matches[1] as $name) {
                $regex = $params[$name] ?? self::DEFAULT_REGEX;
                // Build pairs
                $pairs['{' . $name . '}'] = '(' . $regex . ')';
            }

            $info['vars']  = $matches[1];
            $info['regex'] = '#^' . strtr($path, $pairs) . '$#';
        }

        $this->modules[$path] = $info;
    }

    /**
     * @param string   $modPath
     * @param string   $cmdId
     * @param callable $handler
     * @param array    $info
     */
    public function addCommand(string $modPath, string $cmdId, $handler, array $info = []): void
    {
        $modPath = Str::formatPath($modPath);

        // It's an disabled module
        if (isset($this->disabledModules[$modPath])) {
            return;
        }

        // Set handler
        $info['cmdId']   = $cmdId;
        $info['handler'] = $handler;
        $info['modPath'] = $modPath;

        // Has middleware
        if (!empty($info['middles'])) {
            $fullId = $this->getFullCmdID($modPath, $cmdId);

            $this->addMiddlewares($fullId, $info['middles']);
        }

        unset($info['middles']);
        $this->counter++;
        $this->commands[$modPath][$cmdId] = $info;
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
        $path = Str::formatPath($path);
        if (isset($this->modules[$path])) {
            return $this->modules[$path];
        }

        // If is dynamic route
        foreach ($this->modules as $module) {
            if (!$pathRegex = $module['regex']) {
                continue;
            }

            // Regex match
            $matches = [];
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
     * @param string $modPath
     * @param string $cmdId The message route command ID. like 'home.index'
     *
     * @return array Return match result
     * [
     *    status,
     *    route info
     * ]
     */
    public function matchCommand(string $modPath, string $cmdId): array
    {
        $command  = trim($cmdId);
        $modPath  = Str::formatPath($modPath);
        $baseInfo = ['cmdId' => $command, 'modPath' => $modPath];

        if (!isset($this->commands[$modPath])) {
            return [self::NOT_FOUND, $baseInfo];
        }

        if (isset($this->commands[$modPath][$command])) {
            return [self::FOUND, $this->commands[$modPath][$command]];
        }

        return [self::NOT_FOUND, $baseInfo];
    }

    /**
     * @param string $modPath
     * @param string $cmdId
     *
     * @return string
     */
    public function getFullCmdID(string $modPath, string $cmdId): string
    {
        return $modPath . ':' . $cmdId;
    }

    /**
     * @param string $fullCmdID Full command ID: modPath + ':' + cmdId eg: '/ws-test:chat.send'
     * @param array  $middlewares
     */
    public function addMiddlewares(string $fullCmdID, array $middlewares): void
    {
        $this->middlewares[$fullCmdID] = $middlewares;
    }

    /**
     * @param string $fullCmdID
     *
     * @return array
     */
    public function getMiddlewaresByID(string $fullCmdID): array
    {
        return $this->middlewares[$fullCmdID] ?? [];
    }

    /**
     * @param string $modPath
     * @param string $cmdId
     *
     * @return array
     */
    public function getCmdMiddlewares(string $modPath, string $cmdId): array
    {
        $fullCmdID = $this->getFullCmdID($modPath, $cmdId);

        return $this->middlewares[$fullCmdID] ?? [];
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
     * @return array
     */
    public function getDisabledModules(): array
    {
        return $this->disabledModules;
    }

    /**
     * @param array $paths
     */
    public function setDisabledModules(array $paths): void
    {
        foreach ($paths as $path) {
            $this->disabledModules[$path] = 1;
        }
    }

    /**
     * @return array
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    /**
     * @param array $middlewares
     */
    public function setMiddlewares(array $middlewares): void
    {
        $this->middlewares = $middlewares;
    }
}
