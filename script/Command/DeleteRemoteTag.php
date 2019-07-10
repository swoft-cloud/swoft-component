<?php declare(strict_types=1);

namespace SwoftTool\Command;

use Toolkit\Cli\App;

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
            'usage' => 'tag:delete [DIR]',
            'help'  => <<<STR
Arguments:
  names   The component names

Options:
  --all         Apply for all components
  -t, --tag     Display the project next tag version. eg: v2.0.2

Example:
  {{fullCmd}}
  {{fullCmd}} --only-tag
  {{fullCmd}} -d ../view --next-tag
  {{fullCmd}} -d ../view --next-tag --only-tag

STR,
        ];
    }

    public function __invoke(App $app): void
    {
        foreach ($this->findComponents($app) as $dir) {
            \vdump($dir, \basename($dir));
        }
    }
}
