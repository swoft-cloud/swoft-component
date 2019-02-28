<?php declare(strict_types=1);

namespace Swoft\Console\Concern;

use Swoft\Console\Console;
use Swoft\Console\Helper\FormatUtil;
use Swoft\Console\Helper\Show;
use Swoft\Console\Output\Output;
use Swoft\Console\Router\Router;
use Swoft\Stdlib\Helper\Arr;
use Swoft\Stdlib\Helper\Str;

/**
 * Trait RenderHelpInfoTrait
 * @package Swoft\Console\Concern
 */
trait RenderHelpInfoTrait
{
    /**
     * Display application version
     */
    protected function showVersionInfo(): void
    {
        /** @var Output $output */
        $output = $this->output;

        // version information
        $phpVersion    = \PHP_VERSION;
        $swoftVersion  = \Swoft::VERSION;
        $swooleVersion = \SWOOLE_VERSION;

        // Display logo
        $output->colored(\Swoft::FONT_LOGO);
        // Display some information
        $output->writef(
            'PHP: <info>%s</info>, Swoft: <info>%s</info>, Swoole: <info>%s</info>',
            $phpVersion, $swoftVersion, $swooleVersion
        );
    }

    /**
     * Display command list of the application
     *
     * @param bool $showLogo
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    protected function showApplicationHelp(bool $showLogo = true): void
    {
        // show logo
        if ($showLogo) {
            Console::colored(\Swoft::FONT_LOGO, 'cyan');
        }

        $script = \input()->getScriptName();
        // Global options
        $globalOptions = self::$globalOptions;
        // Append expand option
        $globalOptions['--expand'] = 'Expand sub-commands for all command groups';

        $appVer  = $this->getVersion();
        $appDesc = $this->getDescription();

        Console::startBuffer();
        Console::writeln(\sprintf("%s%s\n", $appDesc, $appVer ? " (Version: <info>$appVer</info>)" : ''));

        Show::mList([
            'Usage:'   => "$script <info>COMMAND</info> [arg0 arg1 arg2 ...] [--opt -v -h ...]",
            'Options:' => FormatUtil::alignOptions($globalOptions),
        ]);

        /* @var Router $router */
        $router   = \Swoft::getBean('cliRouter');
        $expand   = \input()->getBoolOpt('expand');
        $keyWidth = $router->getKeyWidth($expand ? 2 : 0);

        Console::writeln('<comment>Available Commands:</comment>');

        $grpHandler = function (string $group, array $info) use ($keyWidth) {
            Console::writef(
                '  <info>%s</info>%s%s',
                Str::padRight($group, $keyWidth),
                $info['desc'] ?: 'No description message',
                $info['alias'] ? "(alias: <info>{$info['alias']}</info>)" : ''
            );
        };

        $cmdHandler = function (string $cmdId, array $info) use ($keyWidth) {
            // \var_dump($info);die;
            Console::writef(
                '  <info>%s</info> %s%s',
                Str::padRight($cmdId, $keyWidth),
                $info['desc'] ?: 'No description message',
                $info['alias'] ? "(alias: <info>{$info['alias']}</info>)" : ''
            );
        };

        $router->sortedEach($grpHandler, $expand ? $cmdHandler : null);

        Console::write("\nMore command information, please use: <cyan>$script COMMAND -h</cyan>");
        Console::flushBuffer();
    }

    /**
     * Display help, command list of the group
     *
     * @param string $group Group name
     * @param array  $info Some base info of the group
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    protected function showGroupHelp(string $group, array $info = []): void
    {
        /* @var Router $router */
        $router = \Swoft::getBean('cliRouter');
        $script = \input()->getScriptName();

        if (!$info) {
            $info = $router->getGroupInfo($group);
        }

        // $class = $groupInfo['class'];
        $names = $info['names'];
        \sort($names);
        $keyWidth  = $router->getKeyWidth();
        $groupName = \sprintf('%s%s', $group, $info['alias'] ? " (alias: <cyan>{$info['alias']}</cyan>)" : '');

        Console::startBuffer();
        Console::writeln($info['desc'] . \PHP_EOL);
        Console::writeln("<comment>Group:</comment> $groupName");

        Show::mList([
            'Usage:'          => "{$script} {$group}:<info>COMMAND</info> [--opt ...] [arg ...]",
            'Global Options:' => FormatUtil::alignOptions(self::$globalOptions),
        ]);

        Console::writeln('<comment>Available Commands:</comment>');

        foreach ($names as $name) {
            $cmdId = $router->buildCommandID($group, $name);
            $cInfo = $router->getRouteByID($cmdId);
            Console::writef(
                '  <info>%s</info> %s%s',
                Str::padRight($name, $keyWidth),
                $cInfo['desc'] ?: 'No description message',
                $cInfo['alias'] ? "(alias: <info>{$cInfo['alias']}</info>)" : ''
            );
        }

        Console::writef("\nView the specified command, please use: <cyan>%s %s:COMMAND -h</cyan>", $script, $group);
        Console::flushBuffer();
    }

    /**
     * Display help for an command
     *
     * @param array $info
     */
    protected function showCommandHelp(array $info): void
    {
        $script = \input()->getScriptName();

        Console::startBuffer();
        Console::writeln($info['desc'] . \PHP_EOL);

        Show::mList([
            'Usage:'          => \sprintf('%s %s [--opt ...] [arg ...]', $script, $info['cmdId']),
            'Global Options:' => FormatUtil::alignOptions(self::$globalOptions),
        ]);
        // [$className, $method] = $info['handler'];

        // Command options
        if ($options = $info['options']) {
            \ksort($options);
            Console::writeln('<comment>Options:</comment>');

            $maxLen  = 0;
            $newOpts = [];

            foreach ($options as $name => $meta) {
                if (($len = \strlen($name)) === 0) {
                    continue;
                }

                if ($len === 1) {
                    $key = \sprintf('-%s %s', $name, $meta['type']);
                } else {
                    $name .= $meta['short'] ? ', -' . $meta['short'] : '';
                    $key  = \sprintf('--%s %s', $name, $meta['type']);
                }

                $kenLen = \strlen($key);
                if ($kenLen > $maxLen) {
                    $maxLen = $kenLen;
                }

                $newOpts[$key] = $meta;
            }

            // render
            foreach ($newOpts as $key => $meta) {
                Console::writef('  %s    %s', Str::padRight($key, $maxLen), $meta['desc']);
            }
        }

        // Command arguments
        if ($arguments = $info['arguments']) {
            Console::writeln('<comment>Arguments:</comment>');

            $keyWidth = Arr::getKeyMaxWidth($arguments);
            foreach ($arguments as $name => $meta) {
                Console::writef('%s %s %s', Str::padRight($name, $keyWidth), $meta['type'], $meta['desc']);
            }
        }

        Console::flushBuffer();
    }
}
