<?php declare(strict_types=1);

namespace SwoftTool\Command;

use Swoft\Console\Helper\Show;
use Swoft\Stdlib\Helper\PhpHelper;
use Swoft\Stdlib\Helper\Sys;
use Toolkit\Cli\App;

/**
 * Class GitInfo
 *
 * @package SwoftTool\Command
 */
class GitInfo
{
    public function getHelpConfig(): array
    {
        return [
            'name'  => 'git:latest-tag',
            'desc'  => 'get the latest tag from the project directory',
            'usage' => 'gen:latest-tag [DIR]',
            'help'  => <<<STR
Example:
  {{command}} 
STR,
        ];
    }

    public function __invoke(App $app): void
    {
        $cmd = 'git describe --tags $(git rev-list --tags --max-count=1)';
        [$code, $output, ] = Sys::run($cmd);

        \vdump($code, $output);
    }
}
