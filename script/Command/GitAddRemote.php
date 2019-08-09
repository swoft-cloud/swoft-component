<?php declare(strict_types=1);

namespace SwoftTool\Command;

use function basename;
use Swoft\Stdlib\Helper\Sys;
use Toolkit\Cli\App;
use Toolkit\Cli\Color;

/**
 * Class GitAddRemote
 *
 * @package SwoftTool\Command
 */
class GitAddRemote extends BaseCommand
{
    public const REMOTE_PREFIX = 'git@github.com:swoft-cloud/swoft-';

    public function getHelpConfig(): array
    {
        $help = <<<STR
Arguments:
  names   The component names

Options:
  --all         Apply for all components

Example:
  {{fullCmd}} --all
  {{fullCmd}} event
  {{fullCmd}} event config

STR;

        return [
            'name'  => 'git:addrmt',
            'desc'  => 'Add the remote repository address of each component',
            'usage' => 'git:addrmt [options] [arguments]',
            'help'  => $help,
        ];
    }

    public function __invoke(App $app)
    {
        $prefix = self::REMOTE_PREFIX;

        foreach ($this->findComponents($app) as $dir) {
            $name  = basename($dir);
            Color::println("===== Add remote for $name");

            $check = "git remote -v | grep swoft-{$name}.git";
            Color::println('> ' . $check, 'yellow');

            [$code, , ] = Sys::run($check, $this->baseDir);
            if ($code === 0) {
                Color::println("The remote '{$name}' exist, skip add");
                continue;
            }

            $cmd = "git remote add $name $prefix{$name}.git";
            Color::println('> ' . $cmd, 'yellow');

            [$code, $ret, ] = Sys::run($cmd, $this->baseDir);
            if ($code !== 0) {
                echo "Add remote error for '{$name}'. Return: $ret\n";
                continue;
            }

            Color::println('> OK');
        }

        Color::println('Complete');
    }
}
