<?php declare(strict_types=1);

namespace SwoftTool\Command;

use function array_merge;
use function file_exists;
use function file_get_contents;
use function preg_replace;
use Toolkit\Cli\App;
use Toolkit\Cli\Color;
use function trim;

/**
 * Class UpdateSwooleVer
 *
 * @package SwoftTool\Command
 */
class UpdateSwooleVer extends BaseCommand
{
    /**
     * @var int
     */
    private $updated = 0;

    /**
     * @var string
     */
    private $version;

    public function getHelpConfig(): array
    {
        $help = <<<STR
Options:
  -c        Commit to git after update by `git commit`
  -v        The want updated swoole version. eg: 4.4.3
            If is empty, will read from SWOOLE_VERSION. 

Example:
  {{command}} -v 2.0.3

STR;

        return [
            'name'  => 'up:swover',
            'desc'  => 'update the swoole version for all .travis.yml',
            'usage' => 'up:swover -v VERSION',
            'help'  => $help,
        ];
    }

    public function __invoke(App $app): void
    {
        $defVersion = '';
        if (defined('SWOOLE_VERSION')) {
            $defVersion = 'v' . SWOOLE_VERSION;
        }

        if (!$version = $app->getStrOpt('v', $defVersion)) {
            echo Color::render("Please input an version by option: -v\n", 'error');
            return;
        }

        $this->version = 'v' . trim($version, 'v');

        echo Color::render("New swoole version is: {$this->version}\n", 'info');

        // for all
        $app->setOpts(array_merge($app->getOpts(), ['all' => true]));
        foreach ($this->findComponents($app) as $dir) {
            $this->updateSwooleVersion($dir . '.travis.yml', basename($dir));
        }

        $mainDir = $app->getPwd();
        $this->updateSwooleVersion($mainDir . '/.travis.yml', basename($mainDir));

        if ($this->updated > 0 && $app->getBoolOpt('c')) {
            self::gitCommit('update: update the swoole version for .travis.yml');
        }

        echo Color::render("Complete. Updated: {$this->updated}\n", 'cyan');
    }

    private function updateSwooleVersion(string $file, string $cptName): void
    {
        if (!file_exists($file)) {
            Color::println("Skip the component: $cptName", 'mga');
            return;
        }

        $updated = 0;
        // .../swoole-src/archive/v4.4.1.tar.gz
        $regexp  = '#swoole-src/archive/(v\d.\d.\d).tar.gz#';
        $replace = "swoole-src/archive/{$this->version}.tar.gz";
        $content = file_get_contents($file);

        // replace
        $content = preg_replace($regexp, $replace, $content, 1, $updated);
        if ($updated) {
            $this->updated++;
        }

        Color::println("- Updated the component: $cptName");

        file_put_contents($file, $content);
    }
}
