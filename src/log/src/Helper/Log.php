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
    public static function emergency(string $message, ...$params): bool
    {
        return self::getLogger()->emergency(\sprintf($message, ...$params));
    }

    /**
     * @param string $message
     * @param array  $params
     *
     * @return bool
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public static function debug(string $message, ...$params): bool
    {
        if (APP_DEBUG) {
            return self::getLogger()->debug(\sprintf($message, ...$params));
        }
        
        return true;
    }

    /**
     * @param string $message
     * @param array  $params
     *
     * @return bool
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public static function alert(string $message, ...$params): bool
    {
        return self::getLogger()->alert(\sprintf($message, ...$params));
    }

    /**
     * @param string $message
     * @param array  $params
     *
     * @return bool
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public static function info(string $message, ...$params): bool
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
    public static function warning(string $message, ...$params): bool
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
    public static function error(string $message, ...$params): bool
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
     * @param array  $params
     *
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public static function profileStart(string $name, ...$params): void
    {
        self::getLogger()->profileStart(\sprintf($name, ...$params));
    }

    /**
     * @param string   $name
     * @param int      $hit
     * @param int|null $total
     *
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public static function counting(string $name, int $hit, int $total = null): void
    {
        self::getLogger()->counting($name, $hit, $total);
    }

    /**
     * Profile end
     *
     * @param string $name
     * @param array  $params
     *
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public static function profileEnd(string $name, ...$params): void
    {
        self::getLogger()->profileEnd(\sprintf($name, ...$params));
    }

    /**
     * @return Logger
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public static function getLogger(): Logger
    {
        return BeanFactory::getBean('logger');
    }
}
