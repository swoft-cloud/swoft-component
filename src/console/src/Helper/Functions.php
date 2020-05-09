<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
use Swoft\Console\Input\Input;
use Swoft\Console\Output\Output;
use Swoft\Console\Style\Style;

if (!function_exists('input')) {
    /**
     * @return Input
     */
    function input(): Input
    {
        return Swoft::getSingleton(Input::class);
    }
}

if (!function_exists('output')) {
    /**
     * @return Output
     */
    function output(): Output
    {
        return Swoft::getSingleton(Output::class);
    }
}

if (!function_exists('style')) {
    /**
     * @return Style
     */
    function style(): Style
    {
        return Swoft::getSingleton(Style::class);
    }
}
