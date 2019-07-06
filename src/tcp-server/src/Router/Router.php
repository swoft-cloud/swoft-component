<?php declare(strict_types=1);

namespace Swoft\Tcp\Server\Router;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Contract\RouterInterface;
use function count;
use Swoft\Stdlib\Helper\Str;

/**
 * Class Router
 *
 * @Bean("tcpRouter")
 */
class Router implements RouterInterface
{
    // Default var regex
    public const DEFAULT_REGEX = '[^/]+';

    /**
     * @var array
     */
    private $routes = [];

    /**
     * @param string $path
     * @param array  $info
     */
    public function add(string $path, array $info = []): void
    {
        $path = Str::formatPath($path);
        // Re-set path
        $info['path'] = $path;

        // Exist path var. eg: "/users/{id}"
        if (strpos($path, '{') === false) {
            $info['regex'] = '';

            // Add module
            $this->routes[$path] = $info;
            return;
        }

        $matches = [];
        $params  = $info['params'] ?? [];

        // Parse the parameters and replace them with the corresponding regular
        if (preg_match_all('#\{([a-zA-Z_][\w-]*)\}#', $path, $matches)) {
            /** @var array[] $matches */
            $pairs = [];
            foreach ($matches[1] as $name) {
                $regex = $params[$name] ?? self::DEFAULT_REGEX;
                // Build pairs
                $pairs['{' . $name . '}'] = '(' . $regex . ')';
            }

            $info['vars']  = $matches[1];
            $info['regex'] = '#^' . strtr($path, $pairs) . '$#';
        }

        // Add module
        $this->routes[$path] = $info;
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
        if (isset($this->routes[$path])) {
            return $this->routes[$path];
        }

        // If is dynamic route
        foreach ($this->routes as $route) {
            if (!$pathRegex = $route['regex']) {
                continue;
            }

            // Regex match
            $matches = [];
            if (preg_match($pathRegex, $path, $matches)) {
                $params   = [];
                $pathVars = $route['vars'];

                // First is full match.
                array_shift($matches);
                foreach ($matches as $index => $value) {
                    $params[$pathVars[$index]] = $value;
                }

                $route['routeParams'] = $params;
                return $route;
            }
        }

        return [];
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return count($this->routes);
    }
}
