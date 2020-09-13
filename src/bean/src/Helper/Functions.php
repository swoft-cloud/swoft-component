<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

use Swoft\Bean\BeanFactory;
use Swoft\Bean\Container;

if (!function_exists('bean')) {
    /**
     * Get bean by name
     *
     * @param string $name Bean name Or alias Or class name
     *
     * @return object|string|mixed
     */
    function bean(string $name)
    {
        if (BeanFactory::isSingleton('config')) {
            return BeanFactory::getBean($name);
        }

        return sprintf('${%s}', $name);
    }
}

if (!function_exists('container')) {
    /**
     * Get container
     *
     * @return Container
     */
    function container(): Container
    {
        return Container::getInstance();
    }
}
