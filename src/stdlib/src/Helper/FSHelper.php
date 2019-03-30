<?php

namespace Swoft\Stdlib\Helper;

/**
 * Class FSHelper - file system helper
 * @package Swoft\Stdlib\Helper
 */
class FSHelper
{
    /**
     * @param string $path
     * @return string
     */
    public static function formatPath(string $path): string
    {
        if (\DIRECTORY_SEPARATOR === '\\') {
            return \str_replace('\\', '/', $path);
        }

        return $path;
    }
}
