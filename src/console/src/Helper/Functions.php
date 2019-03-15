<?php
if (!function_exists('input')) {
    /**
     * @return \Swoft\Console\Input\Input
     */
    function input(): \Swoft\Console\Input\Input
    {
        return \Swoft::getSingleton(\Swoft\Console\Input\Input::class);
    }
}

if (!function_exists('output')) {
    /**
     * @return \Swoft\Console\Output\Output
     */
    function output(): \Swoft\Console\Output\Output
    {
        return \Swoft::getSingleton(\Swoft\Console\Output\Output::class);
    }
}

if (!function_exists('style')) {
    /**
     * @return \Swoft\Console\Style\Style
     */
    function style(): \Swoft\Console\Style\Style
    {
        return \Swoft::getSingleton(\Swoft\Console\Style\Style::class);
    }
}
