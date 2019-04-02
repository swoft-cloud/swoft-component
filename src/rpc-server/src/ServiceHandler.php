<?php declare(strict_types=1);


namespace Swoft\Rpc\Server;


use Swoft\Rpc\Server\Contract\RequestHandlerInterface;
use Swoft\Rpc\Server\Contract\RequestInterface;
use Swoft\Rpc\Server\Contract\ResponseInterface;

/**
 * Class ServiceHandler
 *
 * @since 2.0
 */
class ServiceHandler implements RequestHandlerInterface
{
    /**
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     */
    public function handle(RequestInterface $request): ResponseInterface
    {
        
    }
}