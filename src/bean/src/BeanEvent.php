<?php declare(strict_types=1);


namespace Swoft\Bean;

/**
 * Class BeanEvent
 *
 * @since 2.0
 */
class BeanEvent
{
    /**
     * Init request bean event
     */
    public const INIT_REQUEST = 'swoft.bean.request.init';

    /**
     * Destroy request bean event
     */
    public const DESTROY_REQUEST = 'swoft.bean.request.destroy';

    /**
     * Init session event
     */
    public const INIT_SESSION = 'swoft.bean.session.init';

    /**
     * Destroy session event
     */
    public const DESTROY_SESSION = 'swoft.bean.session.destroy';
}