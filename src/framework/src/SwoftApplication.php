<?php

namespace Swoft;

use Swoft\Processor\AnnotationProcessor;
use Swoft\Processor\ApplicationProcessor;
use Swoft\Processor\BeanProcessor;
use Swoft\Processor\ConfigProcessor;
use Swoft\Processor\ConsoleProcessor;
use Swoft\Processor\EnvProcessor;
use Swoft\Processor\EventProcessor;
use Swoft\Processor\Processor;
use Swoft\Processor\ProcessorInterface;

/**
 * Swoft application
 */
class SwoftApplication implements SwoftInterface, ApplicationInterface
{
    /**
     * Env file
     *
     * @var string
     */
    protected $env = '.env';

    /**
     * @var ApplicationProcessor
     */
    protected $processor;

    /**
     * Default bean file
     *
     * @var string
     */
    protected $beanFile = '@app/bean.php';

    /**
     * Swoft trait
     */
    use SwoftTrait;

    /**
     * Application constructor.
     */
    public function __construct()
    {
        $processorss = $this->processors();

        $this->processor = new ApplicationProcessor($this);
        $this->processor->addFirstProcessor(...$processorss);
    }

    /**
     * Run application
     */
    public function run(): void
    {
        if ($this->beforeRun()) {
            $this->processor->handle();
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
     * @return string
     */
    public function getBeanFile(): string
    {
        return $this->beanFile;
    }

    /**
     * @return ProcessorInterface[]
     */
    private function processors(): array
    {
        return [
            new EnvProcessor($this),
            new AnnotationProcessor($this),
            new ConfigProcessor($this),
            new BeanProcessor($this),
            new EventProcessor($this),
            new ConsoleProcessor($this),
        ];
    }
}