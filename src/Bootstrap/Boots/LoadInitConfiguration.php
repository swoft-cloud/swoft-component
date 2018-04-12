<?php

namespace Swoft\Bootstrap\Boots;

use Swoft\App;
use Swoft\Bean\Annotation\Bootstrap;
use Swoft\Core\Config;
use Swoft\Helper\DirHelper;

/**
 * @Bootstrap(order=3)
 * @uses      LoadInitConfiguration
 * @version   2017-11-02
 * @author    huangzhhui <huangzhwork@gmail.com>
 * @copyright Copyright 2010-2017 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class LoadInitConfiguration implements Bootable
{
    public function bootstrap()
    {
        $path = App::getAlias('@configs');
        $excludeFiles = [
            $path . DS . 'define.php',
        ];
        $config = new Config();
        $config->load($path, $excludeFiles, DirHelper::SCAN_CURRENT_DIR, Config::STRUCTURE_SEPARATE);
        App::setAppProperties($config);
    }
}
