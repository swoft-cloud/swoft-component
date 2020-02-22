<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Tcp;

/**
 * Class ErrCode
 *
 * @since 2.0.4
 */
class ErrCode
{
    public const SERVER_ERROR    = -10401;

    public const UNPACKING_FAIL  = -10402;

    public const ROUTE_NOT_FOUND = -10403;
}
