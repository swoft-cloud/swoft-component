<?php declare(strict_types=1);

namespace SwoftTool\Command;

use Swoole\Coroutine;
use Toolkit\Cli\App;
use Toolkit\Cli\Color;

/**
 * Class GitSubtreePull
 *
 * @package SwoftTool\Command
 */
class GitSubtreePull extends BaseCommand
{
    public function getHelpConfig(): array
    {
        $help = <<<STR
Arguments:
  names   The component names

Options:
  --all         Apply for all components
  --debug       Open debug mode
  --squash      Append the --squash option for pull

Example:
  {{fullCmd}} --all
  {{fullCmd}} event
  {{fullCmd}} event config

STR;

        return [
            'name'  => 'git:spull',
            'desc'  => 'Pull all update from remote sub-repo by git subtree pull',
            'usage' => 'git:spull [options] [arguments]',
            'help'  => $help,
        ];
    }

    public function __invoke(App $app)
    {
        $targetBranch = 'master';
        $this->debug = $app->getBoolOpt('debug');

        $runner = Scheduler::new();
        $squash = $app->getBoolOpt('squash');

        // git subtree pull --prefix=src/annotation git@github.com:swoft-cloud/swoft-annotation.git master --squash
        // git subtree pull --prefix=src/stdlib stdlib master
        foreach ($this->findComponents($app) as $dir) {
            $name = basename($dir);
            $cmd  = "git subtree pull --prefix=src/{$name} {$name} {$targetBranch}";

            if ($squash) {
                $cmd .= ' --squash';
            }

            $runner->add(function () use ($name, $cmd) {
                Color::println("\n====== Pull the component:【{$name}】");
                Color::println("> $cmd", 'yellow');

                if ($this->debug) {
                    Color::println('[DEBUG] use co::sleep(2) to mock remote operation');
                    Coroutine::sleep(2);
                    return;
                }

                $ret = Coroutine::exec($cmd);
                if ((int)$ret['code'] !== 0) {
                    $msg = "Pull from remote fail of the {$name}. Output: {$ret['output']}";
                    Color::println($msg, 'error');
                    return;
                }

                Color::println("- Complete for {$name}\n", 'cyan');
                Coroutine::sleep(1);
            });
        }

        $runner->start();
        Color::println("\nComplete", 'cyan');
    }
}
