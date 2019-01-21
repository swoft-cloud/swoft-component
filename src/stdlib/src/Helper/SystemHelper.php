<?php

namespace Swoft\Stdlib\Helper;

/**
 * Class SystemHelper
 *
 * @since 2.0
 */
class SystemHelper
{
    /**
     * Set cli process title
     *
     * @param string $title
     *
     * @return bool
     */
    public static function setProcessTitle(string $title): bool
    {
        if (EnvHelper::isMac()) {
            return false;
        }

        if (\function_exists('cli_set_process_title')) {
            return @cli_set_process_title($title);
        }

        return true;
    }
}