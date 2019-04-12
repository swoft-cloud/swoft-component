<?php declare(strict_types=1);


namespace Swoft\Rpc\Server;

/**
 * Class ServiceServerEvent
 *
 * @since 2.0
 */
class ServiceServerEvent
{
    /**
     * Connect
     */
    public const CONNECT = 'swoft.rpc.server.connect';

    /**
     * Close
     */
    public const CLOSE = 'swoft.rpc.server.close';

    /**
     * Before receive
     */
    public const BEFORE_RECEIVE = 'swoft.rpc.server.receive.before';

    /**
     * After receive
     */
    public const AFTER_RECEIVE = 'swoft.rpc.server.receive.after';
}