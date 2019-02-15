<?php declare(strict_types=1);

namespace Swoft\Http\Message\Contract;

use Swoole\Http\Request;

/**
 * Interface ServerRequestInterface
 * @since 2.0
 */
interface ServerRequestInterface extends \Psr\Http\Message\ServerRequestInterface
{
    /**
     * @return Request
     */
    public function getCoRequest(): Request;
}
