<?php declare(strict_types=1);

namespace SwoftTool\Command;

use Generator;
use InvalidArgumentException;
use const PHP_EOL;
use function sprintf;
use Swoft\Stdlib\Helper\Sys;
use Swoole\Coroutine;
use Toolkit\Cli\App;
use Toolkit\Cli\Color;

/**
 * Class BaseCommand
 *
 * @package SwoftTool\Command
 */
abstract class BaseCommand
{
    public const MAIN = 'component';

    /**
     * @var string
     */
    protected $baseDir;

    /**
     * @var string
     */
    protected $libsDir;

    /**
     * @var bool
     */
    protected $debug = false;

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

    /**
     * @param string $cmd
     * @param string $workDir
     * @param bool   $coRun
     *
     * @return array
     */
    public static function exec(string $cmd, string $workDir = '', bool $coRun = false): array
    {
        Color::println("> $cmd", 'yellow');

        if ($coRun) {
            $ret = Coroutine::exec($cmd);
            if ((int)$ret['code'] !== 0) {
                $msg = "Exec command error. Output: {$ret['output']}";
                Color::println($msg, 'error');
            }

            return $ret;
        }

        // normal run
        [$code, $output,] = Sys::run($cmd, $workDir);
        if ($code !== 0) {
            $msg = "Exec command error. Output: {$output}";
            Color::println($msg, 'error');
        }

        return [
            'code'   => $code,
            'output' => $output,
        ];
    }

    /**
     * @param string $message
     */
    public static function gitCommit(string $message): void
    {
        $ret = self::exec(sprintf('git add . && git commit -m "%s"', $message));
        if ((int)$ret['code'] === 0) {
            echo $ret['output'] . PHP_EOL;
        }
    }
}
