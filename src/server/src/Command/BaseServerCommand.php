<?php

namespace Swoft\Server\Command;

use function input;
use Swoft\Server\Server;

/**
 * Class BaseServerCommand
 * @since 2.0
 */
abstract class BaseServerCommand
{
    /**
     * Set startup options to override configuration options
     *
     * @param Server $server
     */
    protected function configStartOption(Server $server): void
    {
        $asDaemon = input()->getSameOpt(['d', 'daemon'], false);
        if ($asDaemon) {
            $server->setDaemonize();
        }
    }
}
