<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Tcp\Server\Contract;

use Swoft\Tcp\Server\Request;
use Swoft\Tcp\Server\Response;

/**
 * Interface MiddlewareInterface
 *
 * @since 2.0.7
 */
interface MiddlewareInterface
{
    /**
     * @param RequestInterface|Request $request
     * @param RequestHandlerInterface  $handler
     *
     * @return ResponseInterface|Response
     */
    public function process(RequestInterface $request, RequestHandlerInterface $handler): ResponseInterface;
}
