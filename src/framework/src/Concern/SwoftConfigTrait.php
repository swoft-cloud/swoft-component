<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Concern;

/**
 * Trait SwoftConfigTrait
 *
 * @since 2.0
 */
trait SwoftConfigTrait
{
    /**
     * @var bool
     */
    protected $startConsole = true;

    /**
     * Can disable processor class before handle.
     * eg.
     * [
     *  Swoft\Processor\ConsoleProcessor::class => 1,
     * ]
     *
     * @var array
     */
    private $disabledProcessors = [];

    /**
     * Can disable AutoLoader class before handle.
     * eg.
     * [
     *  Swoft\Console\AutoLoader::class  => 1,
     * ]
     *
     * @var array
     */
    private $disabledAutoLoaders = [];

    /**
     * Scans containing these namespace prefixes will be excluded.
     *
     * @var array
     * eg.
     * [
     *  'PHPUnit\\',
     * ]
     */
    private $disabledPsr4Prefixes = [];

    /**
     * Get env name
     *
     * Return `true` is to continue
     *
     * @return string
     */
    public function getEnvFile(): string
    {
        return $this->envFile;
    }

    /**
     * @noinspection PhpDocSignatureInspection
     *
     * @param string[] ...$classes
     */
    public function disableAutoLoader(string ...$classes): void
    {
        foreach ($classes as $class) {
            $this->disabledAutoLoaders[$class] = 1;
        }
    }

    /**
     * @noinspection PhpDocSignatureInspection
     *
     * @param string ...$classes
     */
    public function disableProcessor(string ...$classes): void
    {
        foreach ($classes as $class) {
            $this->disabledProcessors[$class] = 1;
        }
    }

    /**
     * Before run
     *
     * Return `true` is to continue
     *
     * @return bool
     */
    public function beforeRun(): bool
    {
        return true;
    }

    /**
     * After env
     *
     * @return bool
     * Return `true` is to continue
     * Return `false` is to stop application
     */
    public function afterEnv(): bool
    {
        return true;
    }

    /**
     * Before env
     *
     * @return bool
     * Return `true` is to continue
     * Return `false` is to stop application
     */
    public function beforeEnv(): bool
    {
        return true;
    }

    /**
     * Before annotation
     *
     * Return `true` is to continue
     * Return `false` is to stop application
     *
     * @return bool
     */
    public function beforeAnnotation(): bool
    {
        return true;
    }

    /**
     * After annotation
     *
     * Return `true` is to continue
     * Return `false` is to stop application
     *
     * @return bool
     */
    public function afterAnnotation(): bool
    {
        return true;
    }

    /**
     * Before config
     *
     * Return `true` is to continue
     * Return `false` is to stop application
     *
     * @return bool
     */
    public function beforeConfig(): bool
    {
        return true;
    }

    /**
     * After annotation
     *
     * Return `true` is to continue
     * Return `false` is to stop application
     *
     * @return bool
     */
    public function afterConfig(): bool
    {
        return true;
    }

    /**
     * Before bean
     *
     * Return `true` is to continue
     * Return `false` is to stop application
     *
     * @return bool
     */
    public function beforeBean(): bool
    {
        return true;
    }

    /**
     * After bean
     *
     * Return `true` is to continue
     * Return `false` is to stop application
     *
     * @return bool
     */
    public function afterBean(): bool
    {
        return true;
    }

    /**
     * Before event
     *
     * Return `true` is to continue
     * Return `false` is to stop application
     *
     * @return bool
     */
    public function beforeEvent(): bool
    {
        return true;
    }

    /**
     * After Event
     *
     * Return `true` is to continue
     * Return `false` is to stop application
     *
     * @return bool
     */
    public function afterEvent(): bool
    {
        return true;
    }

    /**
     * Before console
     *
     * Return `true` is to continue
     * Return `false` is to stop application
     *
     * @return bool
     */
    public function beforeConsole(): bool
    {
        return true;
    }

    /**
     * After console
     *
     * Return `true` is to continue
     * Return `false` is to stop application
     *
     * @return bool
     */
    public function afterConsole(): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function getDisabledProcessors(): array
    {
        return $this->disabledProcessors;
    }

    /**
     * @return array
     */
    public function getDisabledAutoLoaders(): array
    {
        return $this->disabledAutoLoaders;
    }

    /**
     * @return array
     */
    public function getDisabledPsr4Prefixes(): array
    {
        return $this->disabledPsr4Prefixes;
    }

    /**
     * @param array $disabledAutoLoaders
     */
    public function setDisabledAutoLoaders(array $disabledAutoLoaders): void
    {
        $this->disabledAutoLoaders = $disabledAutoLoaders;
    }

    /**
     * @param array $disabledPsr4Prefixes
     */
    public function setDisabledPsr4Prefixes(array $disabledPsr4Prefixes): void
    {
        $this->disabledPsr4Prefixes = $disabledPsr4Prefixes;
    }

    /**
     * @param array $disabledProcessors
     */
    public function setDisabledProcessors(array $disabledProcessors): void
    {
        $this->disabledProcessors = $disabledProcessors;
    }

    /**
     * @return bool
     */
    public function isStartConsole(): bool
    {
        return $this->startConsole;
    }

    /**
     * @param bool $startConsole
     */
    public function setStartConsole($startConsole): void
    {
        $this->startConsole = (bool)$startConsole;
    }
}
