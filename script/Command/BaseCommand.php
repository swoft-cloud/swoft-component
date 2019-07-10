<?php declare(strict_types=1);

namespace SwoftTool\Command;

use Generator;
use InvalidArgumentException;
use Toolkit\Cli\App;
use Toolkit\Cli\Color;

/**
 * Class BaseCommand
 *
 * @package SwoftTool\Command
 */
abstract class BaseCommand
{
    /**
     * @var string
     */
    protected $baseDir;

    /**
     * @var string
     */
    protected $libsDir;

    public function __construct()
    {
        $this->baseDir = BASE_PATH;
        $this->libsDir = BASE_PATH . '/src/';
    }

    /**
     * @param App    $app
     * @param string $allOpt Default is --all
     *
     * @return Generator|void
     */
    protected function findComponents(App $app, string $allOpt = 'all')
    {
        // For all components
        if ($app->getOpt($allOpt, false)) {
            $flags  = GLOB_ONLYDIR | GLOB_MARK;
            $pattern = $this->libsDir . '*';

            yield from glob($pattern, $flags);
            return;
        }

        // For some components
        if (!$names = $app->getArgs()) {
            throw new InvalidArgumentException('Please input component names arguments');
        }

        foreach ($names as $name) {
            $dir = $this->libsDir . $name;
            if (!is_dir($dir)) {
                echo Color::render("Invalid component name: $name\n", 'error');
                continue;
            }

            yield $dir . '/';
        }
    }
}
