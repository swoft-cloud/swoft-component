<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Server\Contract;

use Swoole\Http\Request;
use Swoole\Http\Response;

/**
 * Class RequestInterface
 *
 * @since 2.0
 */
interface RequestInterface
{
    /**
     * Request event
     *
     * @param Request  $request
     * @param Response $response
     */
    public function onRequest(Request $request, Response $response): void;
}
