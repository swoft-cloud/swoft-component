<?php
if (!function_exists('bean')) {
    /**
     * Get bean by name
     *
     * @param string $key
     *
     * @return mixed
     */
    function bean(string $key)
    {
        return \Swoft\Bean\BeanFactory::getBean($key);
    }
}
