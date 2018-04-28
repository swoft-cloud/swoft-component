<?php
if (! function_exists('cookie')) {
    /**
     * @return \Swoft\Http\Message\Cookie\CookieManager
     */
    function cookie()
    {
        return \bean('cookieManager');
    }
}