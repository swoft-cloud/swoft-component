<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Tcp\Server\Context;

use Swoft;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Context\AbstractContext;

/**
 * Class TcpCloseContext - on swoole tcp close event
 *
 * @since 2.0.3
 * @Bean(scope=Bean::PROTOTYPE)
 */
class TcpCloseContext extends AbstractContext
{
    /**
     * @var int
     */
    private $fd;

    /**
     * @var int
     */
    private $reactorId;

    /**
     * @param int $fd
     * @param int $reactorId
     *
     * @return self
     */
    public static function new(int $fd, int $reactorId): self
    {
        /** @var self $ctx */
        $ctx = Swoft::getBean(self::class);

        // Initial properties
        $ctx->fd        = $fd;
        $ctx->reactorId = $reactorId;

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
    public function getReactorId(): int
    {
        return $this->reactorId;
    }
}
