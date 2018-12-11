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
namespace Swoft\Rpc\Server\Event;

/**
 * RPC Server event defines
 */
class RpcServerEvent
{
    /**
     * Before rpc request
     */
    const BEFORE_RECEIVE = 'beforeReceive';

    /**
     * After rpc request
     */
    const AFTER_RECEIVE = 'afterReceive';
}
