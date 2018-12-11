<?php
declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Http\Message\Router;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Handler adapter interface
 */
interface HandlerAdapterInterface
{
    /**
     * execute handler
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param array                                    $handler
     *
     * @return ResponseInterface|mixed
     */
    public function doHandler(ServerRequestInterface $request, array $handler);
}
