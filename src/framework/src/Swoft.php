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
use Swoft\Concern\PathAliasTrait;
use Swoft\Event\EventInterface;
use Swoft\Event\Manager\EventManager;
use Swoft\Http\Server\HttpServer;
use Swoft\I18n\I18n;
use Swoft\Server\Server as SwoftServer;
use Swoft\Stdlib\Reflections;
use Swoft\SwoftApplication;
use Swoft\WebSocket\Server\WebSocketServer;
use Swoole\Server;

/**
 * Swoft is a helper class serving common framework functions.
 *
 * @since 2.0
 */
final class Swoft
{
    use PathAliasTrait;

    /**
     * Swoft version
     */
    public const VERSION = '2.0.10';

    /**
     * Swoft terminal logo
     */
    public const FONT_LOGO = "
   _____               ______     ___    ____
  / ___/      ______  / __/ /_   |__ \  / __ \
  \__ \ | /| / / __ \/ /_/ __/   __/ / / / / /
 ___/ / |/ |/ / /_/ / __/ /_    / __/_/ /_/ /
/____/|__/|__/\____/_/  \__/   /____(_)____/
";

    /**
     * Swoft server start banner logo
     */
    public const BANNER_LOGO_SMALL = "
   ____            _____    ___   ___
  / __/    _____  / _/ /_  |_  | / _ \
 _\ \| |/|/ / _ \/ _/ __/ / __/_/ // /
/___/|__,__/\___/_/ \__/ /____(_)___/
";

    /**
     * Swoft server start banner logo
     */
    public const BANNER_LOGO_FULL = "
   ____            _____    ____                                   __     ___   ___
  / __/    _____  / _/ /_  / __/______ ___ _  ___ _    _____  ____/ /__  |_  | / _ \
 _\ \| |/|/ / _ \/ _/ __/ / _// __/ _ `/  ' \/ -_) |/|/ / _ \/ __/  '_/ / __/_/ // /
/___/|__,__/\___/_/ \__/ /_/ /_/  \_,_/_/_/_/\__/|__,__/\___/_/ /_/\_\ /____(_)___/
";

    /**
     * @var SwoftApplication
     */
    public static $app;

    /**
     * @return SwoftApplication
     */
    public static function app(): SwoftApplication
    {
        return self::$app;
    }

    /**
     * Get main server instance
     *
     * @return SwoftServer|HttpServer|WebSocketServer
     */
    public static function server(): SwoftServer
    {
        return SwoftServer::getServer();
    }

    /**
     * Get swoole server
     *
     * @return Server
     */
    public static function swooleServer(): Server
    {
        return self::server()->getSwooleServer();
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
        return Container::$instance->has($name);
    }

    /**
     * Get bean object by name
     *
     * @param string $name Bean name Or alias Or class name
     *
     * @return object|mixed
     */
    public static function getBean(string $name)
    {
        return Container::$instance->get($name);
    }

    /**
     * @param string $name
     *
     * @return mixed
     * @see Container::getSingleton()
     *
     */
    public static function getSingleton(string $name)
    {
        return Container::$instance->getSingleton($name);
    }

    /*******************************************************************************
     * Some short methods
     ******************************************************************************/

    /**
     * Get an ReflectionClass object by input class.
     *
     * @param string $class
     *
     * @return array
     * @throws ReflectionException
     */
    public static function getReflection(string $class): array
    {
        return Reflections::get($class);
    }

    /**
     * @param string $key
     * @param array  $params
     * @param string $locale
     *
     * @return string
     */
    public static function t(string $key, array $params = [], string $locale = ''): string
    {
        /* @var I18n $i18n */
        $i18n = BeanFactory::getBean('i18n');

        return $i18n->translate($key, $params, $locale);
    }

    /**
     * Trigger an swoft application event
     *
     * @param string|EventInterface $event eg: 'app.start' 'app.stop'
     * @param null|mixed            $target
     * @param array                 $params
     *
     * @return EventInterface
     */
    public static function trigger($event, $target = null, ...$params): EventInterface
    {
        /** @see EventManager::trigger() */
        return BeanFactory::getSingleton('eventManager')->trigger($event, $target, $params);
    }

    /**
     * Trigger an swoft application event. like self::trigger(), but params is array
     *
     * @param string|EventInterface $event
     * @param null|mixed            $target
     * @param array                 $params
     *
     * @return EventInterface
     */
    public static function triggerByArray($event, $target = null, array $params = []): EventInterface
    {
        /** @see EventManager::trigger() */
        return BeanFactory::getSingleton('eventManager')->trigger($event, $target, $params);
    }
}
