<?php declare(strict_types=1);

namespace Swoft\WebSocket\Server\Contract;

/**
 * Interface ResponseInterface
 *
 * @since 2.0
 */
interface ResponseInterface
{
    /**
     * @return int
     */
    public function getFd(): int;
}
