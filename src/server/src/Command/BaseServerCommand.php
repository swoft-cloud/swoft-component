<?php

namespace Swoft\Server\Command;

use function input;
use Swoft\Server\Server;
use Swoft\Stdlib\Helper\Sys;

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

    /**
     * @return string
     */
    protected function getFullCommand(): string
    {
        // Full script
        $script = input()->getScript();

        // Full command
        $command = input()->getFullScript();

        $phpBin = 'php';
        [$ok, $ret,] = Sys::run('which php');
        if ($ok === 0) {
            $phpBin = \trim($ret);
        }

        return sprintf('%s %s %s', $phpBin, $script, $command);
    }
}
