<?php declare(strict_types=1);

use Swoft\Bean\Container;
use Swoft\Event\EventInterface;
use Swoft\Event\Manager\EventManager;

/**
 * Swoft is a helper class serving common framework functions.
 *
 * @since 2.0
 */
class Swoft
{
    use \Swoft\Concern\PathAliasTrait;

    public const VERSION = '2.0.0-beta';

    public const FONT_LOGO = "
 ____                __ _
/ ___|_      _____  / _| |_
\___ \ \ /\ / / _ \| |_| __|
 ___) \ V  V / (_) |  _| |_
|____/ \_/\_/ \___/|_|  \__|
";
    /**
     * @var \Swoft\SwoftApplication
     */
    public static $app;

    /**
     * @return \Swoft\SwoftApplication
     */
    public static function app(): \Swoft\SwoftApplication
    {
        return self::$app;
    }

    /**
     * Get main server instance
     *
     * @return \Swoft\Server\Server|\Swoft\Http\Server\HttpServer|\Swoft\WebSocket\Server\WebSocketServer
     */
    public static function server(): \Swoft\Server\Server
    {
        return \Swoft\Server\Server::getServer();
    }

    /*******************************************************************************
     * bean short methods
     ******************************************************************************/

    /**
     * Whether has bean
     *
     * @param string $name
     *
     * @return bool
     */
    public static function hasBean(string $name): bool
    {
        return Container::getInstance()->has($name);
    }

    /**
     * Get bean object by name
     *
     * @param string $name Bean name Or alias Or class name
     *
     * @return object|mixed
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \ReflectionException
     */
    public static function getBean(string $name)
    {
        return Container::getInstance()->get($name);
    }

    /*******************************************************************************
     * Some short methods
     ******************************************************************************/

    /**
     * Get an ReflectionClass object by input class.
     *
     * @param string $class
     * @return array
     * @throws ReflectionException
     */
    public static function getReflection(string $class): array
    {
        return Container::getInstance()->getReflection($class);
    }

    /**
     * Trigger an swoft application event
     *
     * @param string|EventInterface $event eg: 'app.start' 'app.stop'
     * @param null|mixed            $target
     * @param array                 $params
     *
     * @return EventInterface
     * @throws ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public static function trigger($event, $target = null, ...$params): EventInterface
    {
        /** @see EventManager::trigger() */
        return Container::getInstance()->get('eventManager')->trigger($event, $target, $params);
    }

    /**
     * Trigger an swoft application event. like self::trigger(), but params is array
     *
     * @param mixed      $event
     * @param null|mixed $target
     * @param array      $params
     * @return EventInterface
     * @throws ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public static function triggerByArray($event, $target = null, array $params = []): EventInterface
    {
        /** @see EventManager::trigger() */
        return Container::getInstance()->get('eventManager')->trigger($event, $target, $params);
    }
}
