<?php

namespace Swoft\Aop;


/**
 * Class AutoLoader
 *
 * @since 2.0
 */
class AutoLoader extends \Swoft\AutoLoader
{
    /**
     * Get namespace and dirs
     *
     * @return array
     */
    public function getPrefixDirs(): array
    {
        return [
            __NAMESPACE__ => __DIR__,
        ];
    }
}