<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\WebSocket\Server\Context;

use Swoft;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Context\AbstractContext;

/**
 * Class WsCloseContext - on ws close event
 *
 * @since 2.0
 * @Bean(scope=Bean::PROTOTYPE)
 */
class WsCloseContext extends AbstractContext
{
    /**
     * @var int
     */
    private $fd;

    /**
     * @var int
     */
    private $rid;

    /**
     * @param int $fd
     * @param int $reactorId
     *
     * @return WsCloseContext
     */
    public static function new(int $fd, int $reactorId): self
    {
        /** @var self $ctx */
        $ctx = Swoft::getBean(self::class);

        // Initial properties
        $ctx->fd  = $fd;
        $ctx->rid = $reactorId;

        return $ctx;
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
    public function getRid(): int
    {
        return $this->rid;
    }

    /**
     * @return int
     */
    public function getReactorId(): int
    {
        return $this->rid;
    }
}
