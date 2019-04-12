<?php declare(strict_types=1);

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