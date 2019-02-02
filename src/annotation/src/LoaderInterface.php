<?php

namespace Swoft\Annotation;

/**
 * Class loader interface
 */
interface LoaderInterface
{
    /**
     * Get namespace and dir
     *
     * @return array
     * [
     *     namespace => dir path
     * ]
     */
    public function getPrefixDirs(): array;
}