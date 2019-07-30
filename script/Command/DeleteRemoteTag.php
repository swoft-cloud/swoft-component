<?php declare(strict_types=1);

namespace SwoftTool\Command;

use Swoft\Console\Helper\Show;
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
    /**
     * @var array
     */
    private $result = [];

    public function getHelpConfig(): array
    {
        $help = <<<STR
Arguments:
  names   The component names. If name equals 'component', operate for the main project.

Options:
  --all                 Apply for all components
  --debug               Open debug mode
  -t, --tag <version>   The tag version. eg: v2.0.2

Example:
  {{fullCmd}} -t v2.0.3 --all
  {{fullCmd}} -t v2.0.3 event
  {{fullCmd}} -t v2.0.3 event config

STR;

        return [
            'name'  => 'tag:delete',
            'desc'  => 'delete git remote tag for components',
            'usage' => 'tag:delete [options] [arguments]',
            'help'  => $help,
        ];
    }

    public function __invoke(App $app): void
    {
        $tag = $app->getStrOpt('tag', $app->getStrOpt('t'));
        if (!$tag) {
            Color::println('Please input an exist tag for delete', 'error');
            return;
        }

        // operate the component project
        $first = $app->getArg(0);
        if ($first === self::MAIN) {
            $this->deleteForMainProject($tag);
            return;
        }

        $this->debug = $app->getBoolOpt('debug');

        // create runner
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
                    $this->result[$name] = 'Fail';
                    return;
                }

                $this->result[$name] = 'OK';
                Color::println("- Complete for {$name}\n", 'cyan');
            });
        }

        $runner->start();

        Color::println("\nDelete Tag({$tag}) Complete", 'cyan');
        Show::aList($this->result);
    }

    private function deleteForMainProject(string $tag): void
    {
        $cmd = "git tag --delete $tag && git push origin :refs/tags/$tag";
        Color::println("> $cmd", 'yellow');

        $ret = Coroutine::exec($cmd);
        if ((int)$ret['code'] !== 0) {
            $msg = "Delete remote tag fail of the component. Output: {$ret['output']}";
            Color::println($msg, 'error');
            return;
        }

        Color::println("\nDelete Tag({$tag}) Complete", 'cyan');
    }
}
