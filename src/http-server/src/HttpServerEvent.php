<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Http\Server;

/**
 * Class HttpServerEvent
 *
 * @since 2.0
 */
class HttpServerEvent
{
    /**
     * On http route registered
     */
    public const ROUTE_REGISTERED = 'swoft.http.server.route.registered';

    /**
     * Before request event
     */
    public const BEFORE_REQUEST = 'swoft.http.server.request.before';

    /**
     * After request event
     */
    public const AFTER_REQUEST = 'swoft.http.server.request.after';
}
