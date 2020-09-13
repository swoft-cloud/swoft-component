<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Rpc\Server;

/**
 * Class ServiceServerEvent
 *
 * @since 2.0
 */
class ServiceServerEvent
{
    /**
     * Before connect
     */
    public const BEFORE_CONNECT = 'swoft.rpc.server.connect.before';

    /**
     * Connect
     */
    public const CONNECT = 'swoft.rpc.server.connect';

    /**
     * After connect
     */
    public const AFTER_CONNECT = 'swoft.rpc.server.connect.after';

    /**
     * Before close
     */
    public const BEFORE_CLOSE = 'swoft.rpc.server.close.before';

    /**
     * Close
     */
    public const CLOSE = 'swoft.rpc.server.close';

    /**
     * After close
     */
    public const AFTER_CLOSE = 'swoft.rpc.server.close.after';

    /**
     * Before receive
     */
    public const BEFORE_RECEIVE = 'swoft.rpc.server.receive.before';

    /**
     * After receive
     */
    public const AFTER_RECEIVE = 'swoft.rpc.server.receive.after';
}
