<?php declare(strict_types=1);

namespace SwoftTool\Command;

use function file_put_contents;
use Toolkit\Cli\App;
use Toolkit\Cli\Color;
use function basename;
use function file_get_contents;
use function is_dir;
use function strtr;
use function ucwords;
use const BASE_PATH;
use const GLOB_MARK;
use const GLOB_ONLYDIR;

/**
 * Class GenReadme
 *
 * @package SwoftTool\Command
 */
class GenReadme
{
    /**
     * @var string
     */
    private $tplFile;

    public function getHelpConfig(): array
    {

        return [
            'name'  => 'gen:readme',
            'desc'  => 'generate readme file for swoft component(s)',
            'usage' => 'gen:readme NAME(s)',
            'help'  => <<<STR
Example:
  {{command}} --all
  {{command}} http-server
  {{command}} http-server http-message
STR,
        ];
    }

    public function __construct()
    {
        $this->tplFile = BASE_PATH . '/script/template/readme.tpl';
    }

    public function __invoke(App $app): void
    {
        $libsDir = BASE_PATH . '/src/';

        // For all components
        if ($app->getOpt('all', false)) {
            $flags  = GLOB_ONLYDIR | GLOB_MARK;
            $tplStr = file_get_contents($this->tplFile);

            foreach (\glob($libsDir . '*', $flags) as $dir) {
                $this->genReadmeFile($tplStr, $dir);
            }

            return;
        }

        // For some components
        if (!$names = $app->getArgs()) {
            throw new \InvalidArgumentException('Please input component names');
        }

        $tplStr = file_get_contents($this->tplFile);

        foreach ($names as $name) {
            $dir = $libsDir . $name;

            if (!is_dir($dir)) {
                echo Color::render("Invalid component name: $name\n", 'error');
                continue;
            }

            $this->genReadmeFile($tplStr, $dir . '/');
        }
    }

    private function genReadmeFile(string $str, string $dir): void
    {
        $name = basename($dir);

        echo Color::render("Generate README.md for the component: $name\n", 'info');

        $data = [
            '{{component}}'       => $name,
            '{{componentUpWord}}' => ucwords($name),
        ];

        $str  = strtr($str, $data);
        $file = $dir . 'README.md';

        file_put_contents($file, $str);
    }
}
