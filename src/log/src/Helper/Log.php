<?php declare(strict_types=1);


namespace Swoft\Log\Helper;


use Swoft\Bean\BeanFactory;
use Swoft\Log\Logger;

class Log
{
    /**
     * @param string $message
     * @param array  $params
     *
     * @return bool
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public static function debug(string $message, array $params = []): bool
    {
        return self::getLogger()->debug(\sprintf($message, ...$params));
    }

    /**
     * @param string $message
     * @param array  $params
     *
     * @return bool
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public static function info(string $message, array $params = []): bool
    {
        return self::getLogger()->info(\sprintf($message, ...$params));
    }

    /**
     * @param string $message
     * @param array  $params
     *
     * @return bool
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public static function warning(string $message, array $params = []): bool
    {
        return self::getLogger()->warning(\sprintf($message, ...$params));
    }

    /**
     * @param string $message
     * @param array  $params
     *
     * @return bool
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public static function error(string $message, array $params = []): bool
    {
        return self::getLogger()->error(\sprintf($message, ...$params));
    }

    /**
     * Push log
     *
     * @param string $key
     * @param mixed  $val
     *
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public static function pushLog(string $key, $val): void
    {
        self::getLogger()->pushLog($key, $val);
    }

    /**
     * Profile start
     *
     * @param string $name
     *
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public static function profileStart(string $name): void
    {
        self::getLogger()->profileStart($name);
    }

    /**
     * Profile end
     *
     * @param string $name
     *
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public static function profileEnd(string $name): void
    {
        self::getLogger()->profileEnd($name);
    }

    /**
     * @return Logger
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public static function getLogger(): Logger
    {
        return BeanFactory::getSingleton('logger');
    }
}