<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Rpc\Server;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Concern\PrototypeTrait;
use Swoft\Context\AbstractContext;
use Swoole\Server;

/**
 * Class ServiceConnectContext
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::PROTOTYPE)
 */
class ServiceConnectContext extends AbstractContext
{
    use PrototypeTrait;

    /**
     * @var Server
     */
    protected $server;

    /**
     * @var int
     */
    protected $fd;

    /**
     * @var int
     */
    protected $reactorId;

    /**
     * @param Server $server
     * @param int    $fd
     * @param int    $reactorId
     *
     * @return ServiceConnectContext
     */
    public static function new(Server $server, int $fd, int $reactorId): self
    {
        $instance = self::__instance();

        $instance->server    = $server;
        $instance->fd        = $fd;
        $instance->reactorId = $reactorId;

        return $instance;
    }

    /**
     * @return Server
     */
    public function getServer(): Server
    {
        return $this->server;
    }

    /**
     * @return int
     */
    public function getFd(): int
    {
        return $this->fd;
    }

    /**
     * @return int
     */
    public function getReactorId(): int
    {
        return $this->reactorId;
    }
}
