<?php declare(strict_types=1);

namespace SwoftTool\Command;

use Toolkit\Cli\App;
use Toolkit\Cli\Color;
use function basename;
use const GLOB_MARK;
use const GLOB_ONLYDIR;
use const PHP_EOL;

/**
 * Class ListComponents
 */
class ListComponents
{
    public function getHelpConfig(): array
    {
        $help = <<<STR
Example:
  {{command}}

STR;

        return [
            'name'  => 'list',
            'desc'  => 'list all swoft components in src/ dir',
            'usage' => 'list',
            'help'  => $help,
        ];
    }


    public function __invoke(App $app): void
    {
        $libsDir = BASE_PATH . '/src/';

        Color::println('Components:', 'cyan');
        $flags   = GLOB_ONLYDIR | GLOB_MARK;
        $pattern = $libsDir . '*';

        foreach (glob($pattern, $flags) as $item) {
            echo basename($item), PHP_EOL;
        }
    }
}
