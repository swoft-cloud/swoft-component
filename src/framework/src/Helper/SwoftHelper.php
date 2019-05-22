<?php

namespace Swoft\Helper;

use function implode;

/**
 * Class SwoftHelper
 * @since 2.0
 */
class SwoftHelper
{
    /**
     * @param array $stats
     * @return string
     */
    public static function formatStats(array $stats): string
    {
        $strings = [];
        foreach ($stats as $name => $count) {
            $strings[] = "$name $count";
        }

        return implode(', ', $strings);
    }
}
