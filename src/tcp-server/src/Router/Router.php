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
     * [
     *  'command' => [class, method]
     * ]
     * @var array
     */
    private $routes = [];

    /**
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
     * @param array  $info
     *
     * @throws TcpServerRouteException
     */
    public function add(string $cmd, array $info): void
    {
        if (!$cmd = trim($cmd)) {
            throw new TcpServerRouteException('The tcp server route command cannot be empty');
        }

        // Re-set path
        $info['path'] = $cmd;

        // Add module
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
}
