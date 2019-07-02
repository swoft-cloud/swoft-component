<?php declare(strict_types=1);

namespace Swoft\Server\Command;

use Swoft\Server\Server;
use Swoft\Stdlib\Helper\Sys;
use function input;
use function trim;

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
        // Script file
        $script = input()->getScript();

        // Full command
        $command = input()->getFullScript();

        $phpBin = 'php';
        [$ok, $ret,] = Sys::run('which php');
        if ($ok === 0) {
            $phpBin = trim($ret);
        }

        return sprintf('%s %s %s', $phpBin, $script, $command);
    }
}
