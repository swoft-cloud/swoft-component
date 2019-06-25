<?php declare(strict_types=1);

namespace SwoftTool\Command;

use Toolkit\Cli\App;

/**
 * Class GitInfo
 *
 * @package SwoftTool\Command
 */
class GitInfo
{
    public function __invoke(App $app): void
    {
        // git describe --tags $(git rev-list --tags --max-count=1)
    }
}
