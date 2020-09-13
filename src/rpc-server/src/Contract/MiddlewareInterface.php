<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Rpc\Server\Contract;

/**
 * Class MiddlewareInterface
 *
 * @since 2.0
 */
interface MiddlewareInterface
{
    /**
     * @param RequestInterface        $request
     * @param RequestHandlerInterface $requestHandler
     *
     * @return ResponseInterface
     */
    public function process(RequestInterface $request, RequestHandlerInterface $requestHandler): ResponseInterface;
}
