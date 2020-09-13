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

use Swoft\Http\Message\Request;
use Swoole\WebSocket\Server;

/**
 * Class OpenInterface
 *
 * @since 2.0
 */
interface OpenInterface
{
    /**
     * Open event
     *
     * @param Server  $server
     * @param Request $request
     */
    public function onOpen(Server $server, Request $request): void;
}
