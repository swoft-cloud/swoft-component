<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Bean;

/**
 * Class BeanEvent
 *
 * @since 2.0
 */
class BeanEvent
{
    /**
     * Destroy request bean event
     */
    public const DESTROY_REQUEST = 'swoft.bean.request.destroy';

    /**
     * Destroy session event
     */
    public const DESTROY_SESSION = 'swoft.bean.session.destroy';
}
