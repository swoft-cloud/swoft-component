<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Tcp\Server\Router;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Contract\RouterInterface;
use Swoft\Tcp\Server\Exception\TcpServerRouteException;
use Swoft\Tcp\Server\TcpServerBean;
use function count;
use function trim;

/**
 * Class Router
 *
 * @Bean(TcpServerBean::ROUTER)
 */
class Router implements RouterInterface
{
    /**
     * [
     *  'command name' => [
     *      'command' => 'command name'
     *      'handler' => [class, method],
     *  ]
     * ]
     *
     * @var array
     */
    private $routes = [];

    /**
     * [
     *  'command name' => [middle1, middle2],
     * ]
     *
     * @var array
     */
    private $middlewares = [];

    /**
     * The route command delimiter.
     *
     * @var string
     */
    private $delimiter = '.';

    /**
     * Default command. eg: 'home.index'
     *
     * @var string
     */
    private $defaultCommand = '';

    /**
     * @param string $cmd
     * @param array  $handler [class, method]
     * @param array  $info
     *
     * @throws TcpServerRouteException
     */
    public function add(string $cmd, $handler, array $info = []): void
    {
        if (!$cmd = trim($cmd)) {
            throw new TcpServerRouteException('The tcp server route command cannot be empty');
        }

        if (!$handler) {
            throw new TcpServerRouteException("The tcp server command($cmd) handler cannot be empty");
        }

        // Re-set path and save handler
        $info['command'] = $cmd;
        $info['handler'] = $handler;

        // Has middleware
        if (!empty($info['middles'])) {
            $this->addMiddlewares($cmd, $info['middles']);
        }

        unset($info['middles']);
        $this->routes[$cmd] = $info;
    }

    /**
     * Match route path for find module info
     *
     * @param string $cmd e.g 'home.echo'
     *
     * @return array [status, route info]
     */
    public function match(string $cmd): array
    {
        if (!$cmd = trim($cmd)) {
            return [self::NOT_FOUND, null];
        }

        if (isset($this->routes[$cmd])) {
            return [self::FOUND, $this->routes[$cmd]];
        }

        return [self::NOT_FOUND, null];
    }

    /**
     * @param string $cmd
     * @param array  $middlewares
     */
    public function addMiddlewares(string $cmd, array $middlewares): void
    {
        $this->middlewares[$cmd] = $middlewares;
    }

    /**
     * @param string $cmd
     *
     * @return array
     */
    public function getCmdMiddlewares(string $cmd): array
    {
        return $this->middlewares[$cmd] ?? [];
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return count($this->routes);
    }

    /**
     * @return string
     */
    public function getDelimiter(): string
    {
        return $this->delimiter;
    }

    /**
     * @param string $delimiter
     */
    public function setDelimiter(string $delimiter): void
    {
        $this->delimiter = $delimiter;
    }

    /**
     * @return string
     */
    public function getDefaultCommand(): string
    {
        return $this->defaultCommand;
    }

    /**
     * @param string $defaultCommand
     */
    public function setDefaultCommand(string $defaultCommand): void
    {
        $this->defaultCommand = $defaultCommand;
    }

    /**
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routes;
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
