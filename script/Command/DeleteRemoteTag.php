<?php declare(strict_types=1);

namespace SwoftTool\Command;

use Swoole\Coroutine;
use Toolkit\Cli\App;
use Toolkit\Cli\Color;
use function basename;

/**
 * Class DeleteRemoteTag
 *
 * @package SwoftTool\Command
 */
class DeleteRemoteTag extends BaseCommand
{
    public function getHelpConfig(): array
    {
        return [
            'name'  => 'tag:delete',
            'desc'  => 'delete git remote tag for components',
            'usage' => 'tag:delete [options] [arguments]',
            'help'  => <<<STR
Arguments:
  names   The component names

Options:
  --all                 Apply for all components
  --debug               Open debug mode
  -t, --tag <version>   The tag version. eg: v2.0.2

Example:
  {{fullCmd}} -t v2.0.3 --all
  {{fullCmd}} -t v2.0.3 event
  {{fullCmd}} -t v2.0.3 event config

STR,
        ];
    }

    public function __invoke(App $app): void
    {
        $tag = $app->getOpt('tag', $app->getOpt('t'));
        if (!$tag) {
            Color::println('Please input an exist tag for delete', 'error');
            return;
        }

        $this->debug = $app->getBoolOpt('debug');

        $runner = Scheduler::new();

        foreach ($this->findComponents($app) as $dir) {
            $name = basename($dir);
            $cmd = "git push $name :refs/tags/$tag";

            $runner->add(function () use ($name, $cmd) {
                Color::println("====== Delete remote tag for component:【{$name}】");
                Color::println("> $cmd", 'yellow');

                if ($this->debug) {
                    Color::println('[DEBUG] use co::sleep(1) to mock remote operation');
                    Coroutine::sleep(1);
                    return;
                }

                $ret = Coroutine::exec($cmd);
                if ((int)$ret['code'] !== 0) {
                    $msg = "Delete remote tag fail of the {$name}. Output: {$ret['output']}";
                    Color::println($msg, 'error');
                    return;
                }

                echo "Complete for {$name}. Output:", $ret['output'], "\n";
            });
        }

        $runner->start();

        Color::println("\nComplete", 'cyan');
    }
}
