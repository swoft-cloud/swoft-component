<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\WebSocket\Server\Contract;

use Swoft\WebSocket\Server\Message\Request;
use Swoft\WebSocket\Server\Message\Response;

/**
 * Interface MiddlewareInterface
 *
 * @since 2.0
 */
interface MiddlewareInterface
{
    /**
     * @param RequestInterface|Request        $request
     * @param MessageHandlerInterface $handler
     *
     * @return ResponseInterface|Response
     */
    public function process(RequestInterface $request, MessageHandlerInterface $handler): ResponseInterface;
}
