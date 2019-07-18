<?php declare(strict_types=1);

namespace SwoftTool\Command;

use Toolkit\Cli\App;
use Toolkit\Cli\Color;
use function basename;
use function file_get_contents;
use function file_put_contents;
use function str_replace;
use function strtr;
use function ucwords;
use const BASE_PATH;

/**
 * Class GenReadme
 *
 * @package SwoftTool\Command
 */
class GenReadme extends BaseCommand
{
    /**
     * @var string
     */
    private $tplFile;

    public function getHelpConfig(): array
    {
        $help = <<<STR
Arguments:
  names   The component names

Options:
  --all     Apply for all components

Example:
  {{command}} --all
  {{command}} http-server
  {{command}} http-server http-message

STR;

        return [
            'name'  => 'gen:readme',
            'desc'  => 'generate readme file for swoft component(s)',
            'usage' => 'gen:readme NAME(s)',
            'help'  => $help,
        ];
    }

    public function __construct()
    {
        parent::__construct();

        $this->tplFile = BASE_PATH . '/script/template/readme.tpl';
    }

    public function __invoke(App $app): void
    {
        $tplStr = file_get_contents($this->tplFile);

        foreach ($this->findComponents($app) as $dir) {
            $this->genReadmeFile($tplStr, $dir);
        }

        echo Color::render("Complete\n", 'cyan');
    }

    /**
     * @param string $str
     * @param string $dir
     */
    private function genReadmeFile(string $str, string $dir): void
    {
        $name = basename($dir);

        echo Color::render("Generate README.md for the component: $name\n", 'info');

        $data = [
            '{{component}}'       => $name,
            '{{componentUpWord}}' => ucwords(str_replace('-', ' ', $name)),
        ];

        $str = strtr($str, $data);

        file_put_contents($dir . 'README.md', $str);
    }
}
