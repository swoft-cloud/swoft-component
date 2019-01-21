<?php

namespace Swoft\Server\Event;

/**
 * Class ServerEvent
 *
 * @since 2.0
 */
class ServerEvent
{
    /**
     * Swoft before onStart event
     */
    const ON_START_BEFORE = 'swoft.server.onStart';

    /**
     * Swoft after onStart event
     */
    const ON_START_AFTER = 'swoft.server.onStart.after';
}