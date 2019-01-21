<?php

namespace Swoft\Processor;

/**
 * Processor interface
 */
interface ProcessorInterface
{
    /**
     * Handle processor
     *
     * Return `true` is to continue
     */
    public function handle(): bool;
}