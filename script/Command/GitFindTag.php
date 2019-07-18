<?php declare(strict_types=1);

namespace SwoftTool\Command;

use Swoft\Console\Helper\Show;
use Swoft\Stdlib\Helper\Sys;
use Toolkit\Cli\App;
use function array_pop;
use function explode;
use function implode;
use function is_dir;
use function trim;

/**
 * Class GitInfo
 *
 * @package SwoftTool\Command
 */
class GitFindTag
{
    public function getHelpConfig(): array
    {
        return [
            'name'  => 'tag:find',
            'desc'  => 'get the latest/next tag from the project directory',
            'usage' => 'tag:find [DIR]',
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

        $onlyTag = $app->getBoolOpt('only-tag');
        $nextTag = $app->getBoolOpt('next-tag');

        $tagName = $this->findTag($dir, !$onlyTag);
        if (!$tagName) {
            Show::error('No any tags of the project', -2);
            return;
        }

        $title = 'The latest tag: %s';

        if ($nextTag) {
            $title = "The next tag: %s (current: {$tagName})";
            $nodes = explode('.', $tagName);

            $lastNum = array_pop($nodes);
            $nodes[] = (int)$lastNum + 1;
            $tagName = implode('.', $nodes);
        }

        if ($onlyTag) {
            echo $tagName;
            return;
        }

        Show::writef("<info>$title</info>", $tagName);
    }

    /**
     * @param string $workDir
     * @param bool   $showInfo
     *
     * @return string
     */
    public function findTag(string $workDir, bool $showInfo = false): string
    {
        if (!is_dir($workDir)) {
            return '';
        }

        $cmd = 'git describe --tags $(git rev-list --tags --max-count=1)';

        if ($showInfo) {
            $info = [
                'command' => $cmd,
                'workDir' => $workDir,
            ];
            Show::aList($info, 'info');
        }

        [$code, $tagName,] = Sys::run($cmd, $workDir);
        if ($code !== 0) {
            return '';
        }

        return trim($tagName);
    }

    /**
     * Get next tag version. eg: v2.0.3 => v2.0.4
     *
     * @param string $tagName
     *
     * @return string
     */
    public function buildNextTag(string $tagName): string
    {
        $nodes = explode('.', $tagName);

        $lastNum = array_pop($nodes);
        $nodes[] = (int)$lastNum + 1;

        return implode('.', $nodes);
    }
}
