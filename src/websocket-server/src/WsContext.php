<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2019-02-12
 * Time: 13:03
 */

namespace Swoft\WebSocket\Server;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Context\AbstractContext;
use Swoole\WebSocket\Frame;

/**
 * Class WsContext
 *
 * @since 2.0
 * @Bean(scope=Bean::PROTOTYPE)
 */
class WsContext extends AbstractContext
{
    /**
     * @var Frame
     */
    private $frame;

    /**
     * @param Frame $frame
     */
    public function initialize(Frame $frame): void
    {

    }

    /**
     * @return int
     */
    public function getFd(): int
    {
        return $this->frame->fd;
    }

    /**
     * @return Frame
     */
    public function getFrame(): Frame
    {
        return $this->frame;
    }
}
