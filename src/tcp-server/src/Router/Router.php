<?php declare(strict_types=1);

namespace Swoft\Tcp\Server\Router;

use Swoft\Bean\Annotation\Mapping\Bean;
use function count;

/**
 * Class Router
 *
 * @Bean("tcpRouter")
 */
class Router
{
    /**
     * @var array
     */
    private $routes = [];

    public function add(): void
    {

    }

    public function getCount(): int
    {
        return count($this->routes);
    }
}
