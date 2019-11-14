<?php declare(strict_types=1);

namespace Swoft\Tcp\Server\Contract;

/**
 * Interface RequestHandlerInterface
 *
 * @since 2.0.7
 */
interface RequestHandlerInterface
{
    /**
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     */
    public function handle(RequestInterface $request): ResponseInterface;
}
