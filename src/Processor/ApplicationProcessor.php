<?php

namespace Swoft\Processor;

use Swoft\Stdlib\Helper\ArrayHelper;

/**
 * Application processor
 */
class ApplicationProcessor extends Processor
{
    /**
     * @var ProcessorInterface[]
     */
    private $processors = [];

    /**
     * Handle application processors
     */
    public function handle(): bool
    {

        foreach ($this->processors as $processor) {
            $processor->handle();
        }

        return true;
    }

    /**
     * Add first processor
     *
     * @param Processor[] $processors
     *
     * @return bool
     */
    public function addFirstProcessor(Processor ...$processor): bool
    {
        array_unshift($this->processors, ... $processor);

        return true;
    }

    /**
     * Add last processor
     *
     * @param Processor[] $processors
     *
     * @return bool
     */
    public function addLastProcessor(Processor ...$processor): bool
    {
        array_push($this->processors, ... $processor);

        return true;
    }

    /**
     * Add processors
     *
     * @param int         $index
     * @param Processor[] $processors
     *
     * @return bool
     */
    public function addProcessor(int $index, Processor  ...$processors): bool
    {
        ArrayHelper::insert($this->processors, $index, ...$processors);

        return true;
    }
}