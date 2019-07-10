<?php declare(strict_types=1);

namespace SwoftTool\Command;

use Swoole\Coroutine;
use Swoole\Event;
use Toolkit\Cli\App;
use Toolkit\Cli\Color;

/**
 * Class GitSubtreePush
 *
 * @package SwoftTool\Command
 */
class GitSubtreePush extends BaseCommand
{
    public function getHelpConfig(): array
    {
        return [
            'name'  => 'git:spush',
            'desc'  => 'Push all update to remote sub-repo by git subtree push',
            'usage' => 'git:spush [options] [arguments]',
            'help'  => <<<STR
Arguments:
  names   The component names

Options:
  --all         Apply for all components

Example:
  {{fullCmd}} --all
  {{fullCmd}} event
  {{fullCmd}} event config

STR,
        ];
    }

    public function __invoke(App $app)
    {
        foreach ($this->findComponents($app) as $dir) {
            $name = basename($dir);
            $cmd = "git push $name :refs/tags/$tag";

            Coroutine::create(function () use ($name, $cmd) {
                Color::println("> $cmd", 'yellow');

                $ret = Coroutine::exec($cmd);
                if ((int)$ret['code'] !== 0) {
                    $msg = "Delete remote tag fail of the {$name}. Output: {$ret['output']}";
                    Color::println($msg, 'error');
                    return;
                }

                echo $ret['output'];
            });
        }

        Event::wait();
    }
}
