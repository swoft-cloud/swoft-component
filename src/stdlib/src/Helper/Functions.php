<?php declare(strict_types=1);
if (!function_exists('value')) {
    /**
     * Return the callback value
     *
     * @param mixed $value
     *
     * @return mixed
     */
    function value($value)
    {
        return $value instanceof \Closure ? $value() : $value;
    }
}
