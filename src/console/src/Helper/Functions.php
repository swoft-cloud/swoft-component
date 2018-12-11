<?php
declare(strict_types=1);

/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
if (! function_exists('input')) {
    /**
     * @return \Swoft\Console\Input\Input
     */
    function input()
    {
        return \Swoft\App::getBean(\Swoft\Console\Input\Input::class);
    }
}

if (! function_exists('output')) {
    /**
     * @return \Swoft\Console\Output\Output
     */
    function output()
    {
        return \Swoft\App::getBean(\Swoft\Console\Output\Output::class);
    }
}

if (! function_exists('style')) {
    /**
     * @return \Swoft\Console\Style\Style::class
     */
    function style()
    {
        return \Swoft\App::getBean(\Swoft\Console\Style\Style::class);
    }
}
