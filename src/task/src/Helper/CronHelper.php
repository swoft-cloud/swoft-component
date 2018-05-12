<?php

namespace Swoft\Task\Helper;

use Swoft\App;

/**
 * CronHelper
 */
class CronHelper
{
    /**
     * @return bool
     */
    public static function isCronable(): bool
    {
        if (App::$server === null) {
            return false;
        }

        $serverSetting = App::$server->getServerSetting();
        $cronable      = (bool)$serverSetting['cronable'];

        return $cronable;
    }
}