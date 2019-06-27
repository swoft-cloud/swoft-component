<?php declare(strict_types=1);

namespace SwoftTool\Command;

use Toolkit\Cli\App;

/**
 * Class GitHubInfo
 *
 * @package SwoftTool\Command
 */
class GitHubInfo
{
    public function __invoke(App $app): void
    {
        // @see https://developer.github.com/v3/repos/releases/
        // curl https://api.github.com/repos/swoft-cloud/swoft/releases/latest
    }
}
