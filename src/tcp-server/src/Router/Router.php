<?php declare(strict_types=1);

namespace Swoft\Tcp\Server\Router;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Contract\RouterInterface;
use Swoft\Stdlib\Helper\Str;
use function count;
use Swoft\Tcp\Server\Exception\TcpServerRouteException;
use function trim;

/**
 * Class Router
 *
 * @Bean("tcpRouter")
 */
class Router implements RouterInterface
{
    /**
     * @var array
     */
    private $routes = [];

    /**
     * @var string
     */
    private $delimiter = '.';

    /**
     * @param string $path
     * @param array  $info
     *
     * @throws TcpServerRouteException
     */
    public function add(string $path, array $info): void
    {
        if (!$path = trim($path)) {
            throw new TcpServerRouteException('The tcp server route path cannot be empty');
        }

        // Re-set path
        $info['path'] = $path;

        // Add module
        $this->routes[$path] = $info;
    }

    /**
     * Match route path for find module info
     *
     * @param string $path e.g 'home.echo'
     *
     * @return array [status, route info]
     */
    public function match(string $path): array
    {
        if (!$path = trim($path)) {
            return [self::NOT_FOUND, null];
        }

        if (isset($this->routes[$path])) {
            return [self::FOUND, $this->routes[$path]];
        }

        return [self::NOT_FOUND, null];
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
}
