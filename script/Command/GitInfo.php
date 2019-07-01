<?php declare(strict_types=1);

namespace SwoftTool\Command;

use function array_pop;
use function explode;
use function implode;
use Swoft\Console\Helper\Show;
use Swoft\Stdlib\Helper\PhpHelper;
use Swoft\Stdlib\Helper\Sys;
use Toolkit\Cli\App;
use function trim;

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
            'name'  => 'git:tag',
            'desc'  => 'get the latest/next tag from the project directory',
            'usage' => 'gen:latest-tag [DIR]',
            'help'  => <<<STR
Options:
  --dir, -d      The project directory path. default is current directory.
  --next-tag     Display the project next tag version. eg: v2.0.2 => v2.0.3
  --only-tag     Only output tag information

Example:
  {{fullCmd}}
  {{fullCmd}} --only-tag
  {{fullCmd}} -d ../view --next-tag
  {{fullCmd}} -d ../view --next-tag --only-tag

STR,
        ];
    }

    /**
     * echo $(php dtool.php git:tag --only-tag -d ../view)
     *
     * @param App $app
     */
    public function __invoke(App $app): void
    {
        $dir = $app->getOpt('dir', $app->getOpt('d'));
        $dir = $dir ?: $app->getPwd();

        $cmd = 'git describe --tags $(git rev-list --tags --max-count=1)';

        $onlyTag = $app->getBoolOpt('only-tag');
        $nextTag = $app->getBoolOpt('next-tag');

        if (!$onlyTag) {
            $info = [
                'project' => $dir,
                'command' => $cmd,
            ];
            Show::aList($info, 'info');
        }

        // run
        [$code, $tagName, ] = Sys::run($cmd, $dir);

        if ($code !== 0) {
            Show::error('No any tags of the project', -2);
            return;
        }

        $tagName = trim($tagName);
        $title  = 'The latest tag: %s';

        $nodes = explode('.', $tagName);
        $lastNum = array_pop($nodes);

        if ($nextTag) {
            $title   = "The next tag: %s (current: $tagName)";
            $nodes[] = (int)$lastNum + 1;
            $tagName = implode('.', $nodes);
        }

        if ($onlyTag) {
            echo $tagName;
            return;
        }

        Show::writef("<info>$title</info>", $tagName);
    }
}
