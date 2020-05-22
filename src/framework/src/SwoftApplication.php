<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft;

use Swoft;
use Swoft\Concern\SwoftConfigTrait;
use Swoft\Console\Console;
use Swoft\Contract\ApplicationInterface;
use Swoft\Contract\SwoftInterface;
use Swoft\Helper\SwoftHelper;
use Swoft\Log\Helper\CLog;
use Swoft\Processor\AnnotationProcessor;
use Swoft\Processor\ApplicationProcessor;
use Swoft\Processor\BeanProcessor;
use Swoft\Processor\ConfigProcessor;
use Swoft\Processor\ConsoleProcessor;
use Swoft\Processor\EnvProcessor;
use Swoft\Processor\EventProcessor;
use Swoft\Processor\Processor;
use Swoft\Processor\ProcessorInterface;
use Swoft\Stdlib\Helper\ComposerHelper;
use Swoft\Stdlib\Helper\FSHelper;
use Swoft\Stdlib\Helper\ObjectHelper;
use Swoft\Stdlib\Helper\Str;
use Throwable;
use function define;
use function defined;
use function dirname;
use function get_class;
use function sprintf;
use const IN_PHAR;

/**
 * Swoft application
 *
 * @since 2.0.0
 */
class SwoftApplication implements SwoftInterface, ApplicationInterface
{
    use SwoftConfigTrait;

    /**
     * Base path
     *
     * @var string
     */
    protected $basePath = '';

    /**
     * Env file
     *
     * @var string
     */
    protected $envFile = '@base/.env';

    /**
     * Default bean file
     *
     * @var string
     */
    protected $beanFile = '@app/bean.php';

    /**
     * Application path
     *
     * @var string
     */
    protected $appPath = '@base/app';

    /**
     * Config path
     *
     * @var string
     */
    protected $configPath = '@base/config';

    /**
     * Runtime path
     *
     * @var string
     */
    protected $runtimePath = '@base/runtime';

    /**
     * @var string
     */
    protected $resourcePath = '@base/resource';

    /**
     * @var ApplicationProcessor
     */
    private $processor;

    /**
     * Get the application version
     *
     * @return string
     */
    public function getVersion(): string
    {
        return Swoft::VERSION;
    }

    /**
     * Class constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        // Check runtime env
        SwoftHelper::checkRuntime();

        // Storage as global static property.
        Swoft::$app = $this;

        // Before init
        $this->beforeInit();

        // Init console logger
        $this->initCLogger();

        // Can setting properties by array
        if ($config) {
            ObjectHelper::init($this, $config);
        }

        // Init application
        $this->init();

        // After init
        $this->afterInit();
    }

    protected function beforeInit(): void
    {
        // Check phar env
        if (!defined('IN_PHAR')) {
            define('IN_PHAR', false);
        }
    }

    protected function init(): void
    {
        // Init system path aliases
        $this->findBasePath();
        $this->setSystemAlias();

        $processors = $this->processors();

        $this->processor = new ApplicationProcessor($this);
        $this->processor->addFirstProcessor(...$processors);
    }

    protected function afterInit(): void
    {
        // If run in phar package
        if (IN_PHAR) {
            $runtimePath = Swoft::getAlias($this->runtimePath);
            $this->setRuntimePath(Str::rmPharPrefix($runtimePath));
        }

        // Do something ...
        // $this->disableProcessor(ConsoleProcessor::class, EnvProcessor::class);
    }

    private function findBasePath(): void
    {
        if ($this->basePath) {
            return;
        }

        // Get bash path from current class file.
        $filePath = ComposerHelper::getClassLoader()->findFile(static::class);
        $filePath = FSHelper::conv2abs($filePath, false);

        $this->basePath = dirname($filePath, 2);
    }

    /**
     * Run application
     */
    public function run(): void
    {
        try {
            if (!$this->beforeRun()) {
                return;
            }

            $this->processor->handle();
        } catch (Throwable $e) {
            Console::colored(sprintf('%s(code:%d) %s', get_class($e), $e->getCode(), $e->getMessage()), 'red');
            Console::colored('Code Trace:', 'comment');
            echo $e->getTraceAsString(), "\n";
        }
    }

    /**
     * Add first processors
     *
     * @param Processor[] $processors
     *
     * @return bool
     */
    public function addFirstProcessor(Processor ...$processors): bool
    {
        return $this->processor->addFirstProcessor(...$processors);
    }

    /**
     * Add last processors
     *
     * @param Processor[] $processors
     *
     * @return true
     */
    public function addLastProcessor(Processor ...$processors): bool
    {
        return $this->processor->addLastProcessor(...$processors);
    }

    /**
     * Add processors
     *
     * @param int         $index
     * @param Processor[] $processors
     *
     * @return true
     */
    public function addProcessor(int $index, Processor ...$processors): bool
    {
        return $this->processor->addProcessor($index, ... $processors);
    }

    /**
     * @return ProcessorInterface[]
     */
    protected function processors(): array
    {
        return [
            new EnvProcessor($this),
            new ConfigProcessor($this),
            new AnnotationProcessor($this),
            new BeanProcessor($this),
            new EventProcessor($this),
            new ConsoleProcessor($this),
        ];
    }

    /**
     * @param string $beanFile
     */
    public function setBeanFile(string $beanFile): void
    {
        $this->beanFile = $beanFile;
    }

    /**
     * @return string
     */
    public function getBeanFile(): string
    {
        return $this->beanFile;
    }

    /**
     * @param string $relativePath
     *
     * @return string
     */
    public function getPath(string $relativePath): string
    {
        return $this->basePath . '/' . $relativePath;
    }

    /**
     * @return string
     */
    public function getBasePath(): string
    {
        return $this->basePath;
    }

    /**
     * @return string
     */
    public function getAppPath(): string
    {
        return $this->appPath;
    }

    /**
     * @return string
     */
    public function getRuntimePath(): string
    {
        return $this->runtimePath;
    }

    /**
     * @return string
     */
    public function getConfigPath(): string
    {
        return $this->configPath;
    }

    /**
     * @param string $envFile
     */
    public function setEnvFile(string $envFile): void
    {
        $this->envFile = $envFile;
    }

    /**
     * @param string $appPath
     */
    public function setAppPath(string $appPath): void
    {
        $this->appPath = $appPath;

        Swoft::setAlias('@app', $appPath);
    }

    /**
     * @param string $configPath
     */
    public function setConfigPath(string $configPath): void
    {
        $this->configPath = $configPath;

        Swoft::setAlias('@config', $configPath);
    }

    /**
     * @param string $basePath
     */
    public function setBasePath(string $basePath): void
    {
        $this->basePath = $basePath;

        Swoft::setAlias('@base', $basePath);
    }

    /**
     * @param string $runtimePath
     */
    public function setRuntimePath(string $runtimePath): void
    {
        $this->runtimePath = $runtimePath;

        Swoft::setAlias('@runtime', $runtimePath);
    }

    /**
     * Get console logger config
     *
     * @return array
     */
    public function getCLoggerConfig(): array
    {
        return [
            'name'    => 'swoft',
            'enable'  => true,
            'output'  => true,
            'levels'  => '',
            'logFile' => ''
        ];
    }

    /**
     * @return string
     */
    public function getResourcePath(): string
    {
        return $this->resourcePath;
    }

    /**
     * @param string $resourcePath
     */
    public function setResourcePath(string $resourcePath): void
    {
        $this->resourcePath = $resourcePath;
    }

    /**
     * Init console logger
     */
    private function initCLogger(): void
    {
        // Console logger config
        $config = $this->getCLoggerConfig();

        // Init console log
        CLog::init($config);
    }

    /**
     * Set base path
     */
    private function setSystemAlias(): void
    {
        $basePath     = $this->getBasePath();
        $appPath      = $this->getAppPath();
        $configPath   = $this->getConfigPath();
        $runtimePath  = $this->getRuntimePath();
        $resourcePath = $this->getResourcePath();

        Swoft::setAlias('@base', $basePath);
        Swoft::setAlias('@app', $appPath);
        Swoft::setAlias('@config', $configPath);
        Swoft::setAlias('@runtime', $runtimePath);
        Swoft::setAlias('@resource', $resourcePath);

        CLog::info('Project path: @base=%s', $basePath);
        CLog::info('Set alias @app=%s', $appPath);
        CLog::info('Set alias @config=%s', $configPath);
        CLog::info('Set alias @runtime=%s', $runtimePath);
    }
}
