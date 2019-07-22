<?php declare(strict_types=1);

namespace SwoftTool\Command;

use Swoft\Console\Helper\Show;
use Swoole\Coroutine;
use Toolkit\Cli\App;
use Toolkit\Cli\Color;

/**
 * Class GitForcePush
 *
 * @package SwoftTool\Command
 */
class GitForcePush extends BaseCommand
{
    public function getHelpConfig(): array
    {
        $help = <<<STR
Arguments:
  names   The component names

Options:
  --all         Apply for all components
  --debug       Open debug mode

Example:
  {{fullCmd}} --all
  {{fullCmd}} event
  {{fullCmd}} event config

STR;

        return [
            'name'  => 'git:fpush',
            'desc'  => 'Force push all update to remote sub-repo by git push with --force',
            'usage' => 'git:fpush [options] [arguments]',
            'help'  => $help,
        ];
    }

    public function __invoke(App $app)
    {
        $targetBranch = 'master';
        $this->debug = $app->getBoolOpt('debug');

        $result = [];
        $runner = Scheduler::new();

        // force push:
        // git push tcp-server `git subtree split --prefix src/tcp-server master`:master --force
        foreach ($this->findComponents($app) as $dir) {
            $name = basename($dir);
            // push 加 --squash 是没有意义的
            // link https://stackoverflow.com/questions/20102594/git-subtree-push-squash-does-not-squash
            $cmd = "git push {$name} `git subtree split --prefix src/{$name} master`:{$targetBranch} --force";

            $runner->add(function () use ($name, $cmd, &$result) {
                Color::println("\n====== Push the component:【{$name}】");
                Color::println("> $cmd", 'yellow');

                $ret = Coroutine::exec($cmd);
                if ((int)$ret['code'] !== 0) {
                    $msg = "Push to remote fail of the {$name}. Output: {$ret['output']}";
                    Color::println($msg, 'error');
                    $result[$name] = 'Fail';
                    return;
                }

                $result[$name] = 'OK';
                Color::println("- Complete for {$name}\n", 'cyan');
                Coroutine::sleep(1);
            });
        }

        $runner->start();
        Color::println("\nComplete", 'cyan');
        Show::aList($result);
    }
}
