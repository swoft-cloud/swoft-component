<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Http\Server\Contract;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;

/**
 * Class MiddlewareInterface
 *
 * @since 2.0
 */
interface MiddlewareInterface extends \Psr\Http\Server\MiddlewareInterface
{
    /**
     * Process an incoming server request.
     *
     * @param ServerRequestInterface|Request   $request
     * @param RequestHandlerInterface|Response $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface;
}
