<?php

namespace Swoft\Annotation\Resource;

/**
 * Resource interface
 */
interface ResourceInterface
{
    /**
     * Load annotation resource
     */
    public function load(): void;
}