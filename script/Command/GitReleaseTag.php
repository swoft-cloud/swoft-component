<?php declare(strict_types=1);

namespace SwoftTool\Command;

use Toolkit\Cli\App;

/**
 * Class GitReleaseTag
 *
 * @package SwoftTool\Command
 */
class GitReleaseTag extends BaseCommand
{
    public function getHelpConfig(): array
    {
        return [
            'name'  => 'tag:release',
            'desc'  => 'Release all sub-repo to new tag version and push to remote repo',
            'usage' => 'tag:release [options] [arguments]',
            'help'  => <<<STR
Arguments:
  names   The component names

Options:
  --all                 Apply for all components
  --recopy              Recopy components codes to tmp dir for operation
  -t, --tag <version>   The tag version. eg: v2.0.2
  -y                    No confirmation required

Example:
  {{fullCmd}} -t v2.0.3 --all
  {{fullCmd}} -t v2.0.3 event
  {{fullCmd}} -t v2.0.3 event config

STR,
        ];
    }

    public function __invoke(App $app)
    {
        $targetBranch = 'master';
    }
}
