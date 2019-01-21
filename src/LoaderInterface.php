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
     */
    public function getPrefixDirs(): array;
}