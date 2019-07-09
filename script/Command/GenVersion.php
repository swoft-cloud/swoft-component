<?php declare(strict_types=1);

namespace SwoftTool\Command;

use function dirname;
use InvalidArgumentException;
use function preg_match;
use function preg_replace;
use function sprintf;
use Toolkit\Cli\App;
use Toolkit\Cli\Color;
use function basename;
use function file_get_contents;
use function file_put_contents;
use function glob;
use function is_dir;
use function str_replace;
use function strtr;
use function ucwords;
use const BASE_PATH;

/**
 * Class GenReadme
 *
 * @package SwoftTool\Command
 */
class GenVersion
{
    public const REPLACE_WORDS = '"type": "library",';
    public const MATCH_VERSION = '/"version": "[\w.]+"/';

    /**
     * @var string
     */
    private $version;

    public function getHelpConfig(): array
    {
        return [
            'name'  => 'gen:readme',
            'desc'  => 'generate readme file for swoft component(s)',
            'usage' => 'gen:readme NAME(s)',
            'help'  => <<<STR
Example:
  {{command}} --all -v v2.0.3
  {{command}} -v v2.0.3 http-server
  {{command}} -v v2.0.3 http-server http-message

STR,
        ];
    }

    public function __construct()
    {
        // $this->tplFile = BASE_PATH . '/script/template/readme.tpl';
    }

    public function __invoke(App $app): void
    {
        $libsDir = BASE_PATH . '/src/';

        if (!$version = $app->getStrOpt('v')) {
            echo Color::render("Please input an version by option: -v\n", 'error');
            return;
        }

        $this->version = $version;

        echo Color::render("Input new version is: $version\n", 'info');

        // For all components
        if ($app->getOpt('all', false)) {
            $flags   = 0;
            $pattern = $libsDir . 'composer.json';

            foreach (glob($pattern, $flags) as $file) {
                $this->addVersionToComposer($file);
            }
            return;
        }

        // For some components
        if (!$names = $app->getArgs()) {
            throw new InvalidArgumentException('Please input component names');
        }

        foreach ($names as $name) {
            $dir = $libsDir . $name;

            if (!is_dir($dir)) {
                echo Color::render("Invalid component name: $name\n", 'error');
                continue;
            }

            $this->addVersionToComposer($dir . '/composer.json', $name);
        }
    }

    /**
     * @param string $file
     * @param string $name
     */
    private function addVersionToComposer(string $file, string $name = ''): void
    {
        $text = file_get_contents($file);
        $name = $name ?: basename(dirname($file));

        $replace = sprintf('"version": "%s"', $this->version);

        $count = 0;
        $text  = preg_replace(self::MATCH_VERSION, $replace, $text, 1, $count);

        // Not found, is first add.
        if (1 !== $count) {
            $replace = self::REPLACE_WORDS . "\n  {$replace},";
            $text = str_replace(self::REPLACE_WORDS, $replace, $text, $count);
        }

        if (0 === $count) {
            echo Color::render("Failed for add version for component: $name\n", 'error');
            return;
        }

        echo Color::render("Append version for the component: $name\n", 'info');

        file_put_contents($file, $text);
    }
}
