<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Server\Command;

use Swoft;
use Swoft\Console\Console;
use Swoft\Console\Helper\FormatUtil;
use Swoft\Console\Helper\Show;
use Swoft\Server\Contract\ServerInterface;
use Swoft\Server\Server;
use Swoft\Stdlib\Helper\Sys;
use function input;
use function output;
use function sprintf;
use function strtoupper;
use function trim;

/**
 * Class BaseServerCommand
 *
 * @since 2.0
 */
abstract class BaseServerCommand
{
    /**
     * Show server information panel in terminal
     *
     * @param Server $server
     */
    protected function showServerInfoPanel(Server $server): void
    {
        $this->showSwoftBanner();

        // Check if it has started
        if ($server->isRunning()) {
            $masterPid = $server->getPid();
            output()->writeln("<error>The server have been running!(PID: {$masterPid})</error>");
            return;
        }

        // Startup config
        $this->configStartOption($server);

        // Server startup parameters
        $sType = $server->getServerType();

        // Main server info
        $panel = [
            $sType => $this->buildMainServerInfo($server),
        ];

        // Port listeners
        $panel = $this->appendPortsToPanel($server, $panel);
        $title = sprintf('SERVER INFORMATION(v%s)', Swoft::VERSION);

        // Show server info
        Show::panel($panel, $title, [
            'titleStyle' => 'cyan',
        ]);

        $bgMsg = '!';
        if ($server->isDaemonize()) {
            $bgMsg = '(Run in background)!';
        }

        output()->writef("<success>$sType Server Start Success{$bgMsg}</success>");
    }

    /**
     * Show swoft logo banner
     */
    protected function showSwoftBanner(): void
    {
        [$width,] = Sys::getScreenSize();
        $logoText = $width > 90 ? Swoft::BANNER_LOGO_FULL : Swoft::BANNER_LOGO_SMALL;
        $logoText = ltrim($logoText, "\n");

        Console::colored(FormatUtil::applyIndent($logoText), 'cyan');
    }

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
     * @param Server $server
     *
     * @return array
     */
    protected function buildMainServerInfo(Server $server): array
    {
        // Server setting
        $settings  = $server->getSetting();
        $workerNum = $settings['worker_num'];

        $mainHost = $server->getHost();
        $mainPort = $server->getPort();

        $info = [
            'listen' => $mainHost . ':' . $mainPort,
            // 'type'   => $server->getTypeName(),
            'Mode'   => $server->getModeName(),
            'Worker' => $workerNum,
        ];

        $taskNum = $settings['task_worker_num'] ?? 0;
        if ($taskNum > 0) {
            $info['Task worker'] = $taskNum;
        }

        return $info;
    }

    /**
     * @param Server $server
     * @param array  $panel
     *
     * @return array
     */
    protected function appendPortsToPanel(Server $server, array $panel): array
    {
        // Port Listeners
        $listeners = $server->getListener();

        foreach ($listeners as $name => $listener) {
            if (!$listener instanceof ServerInterface) {
                continue;
            }

            $upperName         = strtoupper($name);
            $panel[$upperName] = [
                'listen' => $listener->getHost() . ':' . $listener->getPort(),
                // 'type'   => $listener->getTypeName(),
                '(attached)'
            ];
        }

        return $panel;
    }

    /**
     * Reload Server - reload worker processes
     *
     * @param Server $server
     */
    protected function reloadServer(Server $server): void
    {
        $script = input()->getScriptFile();

        // Check if it has started
        if (!$server->isRunning()) {
            output()->writeln('<error>The server is not running! cannot reload</error>');
            return;
        }

        output()->writef('<info>Server %s is reloading</info>', $script);

        if ($reloadTask = input()->hasOpt('t')) {
            Show::notice('Will only reload task worker');
        }

        if (!$server->reload($reloadTask)) {
            Show::error('The swoole server worker process reload fail!');
            return;
        }

        output()->writef('<success>Server %s reload success</success>', $script);
    }

    /**
     * @param Server $server
     */
    protected function restartServer(Server $server): void
    {
        // If it's has started, stop old server.
        if ($server->isRunning()) {
            $success = $server->stop();

            if (!$success) {
                output()->error('Stop the old server failed!');
                return;
            }
        }

        output()->writef('<success>Swoft Server Restart Success!</success>');

        // Restart server
        $server->startWithDaemonize();
    }

    /**
     * @return string
     */
    protected function getFullCommand(): string
    {
        // Script file
        $script = input()->getScriptFile();

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
