<?php declare(strict_types=1);

if (!function_exists('bean')) {
    /**
     * Get bean by name
     *
     * @param string $name Bean name Or alias Or class name
     *
     * @return object|string
     * @throws ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    function bean(string $name)
    {
        if (\Swoft\Bean\BeanFactory::isSingleton('config')) {
            return \Swoft\Bean\BeanFactory::getBean($name);
        }

        return \sprintf('${%s}', $name);
    }
}

if (!function_exists('container')) {
    /**
     * Get container
     *
     * @return \Swoft\Bean\Container
     */
    function container(): \Swoft\Bean\Container
    {
        return \Swoft\Bean\Container::getInstance();
    }
}