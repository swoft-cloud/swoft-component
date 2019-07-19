<?php declare(strict_types=1);

namespace SwoftTool\Command;

use Toolkit\Cli\App;
use Toolkit\Cli\Color;
use function defined;
use function dirname;
use function in_array;

/**
 * Class GenTravisYml
 */
class GenTravisYml extends BaseCommand
{
    /**
     * @var string
     */
    private $tplFile;

    /**
     * @var string
     */
    private $version;

    public function __construct()
    {
        parent::__construct();

        $this->tplFile = dirname(__DIR__) . '/template/.travis.yml.tpl';
    }

    public function getHelpConfig(): array
    {
        $help = <<<STR
Arguments:
  names   The component names

Options:
  --all     Apply for all components
  -v        The want added swoole version. eg: v4.4.1

Example:
  {{command}} -v v4.4.1 --all
  {{command}} -v v4.4.1 http-server
  {{command}} -v v4.4.1 http-server http-message

STR;

        return [
            'name'  => 'gen:travis',
            'desc'  => 'generate an travis yml file for components',
            'usage' => 'gen:travis NAME(s)',
            'help'  => $help,
        ];
    }

    public function __invoke(App $app): void
    {
        $defVersion = '';
        if (defined('SWOOLE_VERSION')) {
            $defVersion = 'v' . SWOOLE_VERSION;
        }

        if (!$version = $app->getStrOpt('v', $defVersion)) {
            echo Color::render("Please input an version by option: -v\n", 'error');
            return;
        }

        $this->version = $version;

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
        if (in_array($name, ['db', 'redis'], true)) {
            echo Color::render("Skip the component: $name\n", 'yellow');
            return;
        }

        echo Color::render("Generate .travis.yml for the component: $name\n", 'info');

        $data = [
            '{{SWOOLE_VERSION}}' => $this->version,
        ];

        file_put_contents($dir . '.travis.yml', strtr($str, $data));
    }
}
