<?php
/**
 * Created by PhpStorm.
 * User: Inhere
 * Date: 2018/4/19 0019
 * Time: 23:56
 */

namespace Swoft\Http\Server\Helper;

/**
 * Class RouteHelper
 * @package Swoft\Http\Server\Helper
 */
class RouteHelper
{
    /**
     * check route path is static route
     * @param string $route
     * @return bool
     */
    public static function isStaticRoute(string $route): bool
    {
        return \strpos($route, '{') === false && \strpos($route, '[') === false;
    }

    /**
     * format URI path
     * @param string $path
     * @param bool   $ignoreLastSlash
     * @return string
     */
    public static function formatPath(string $path, bool $ignoreLastSlash = true): string
    {
        if ($path === '/') {
            return '/';
        }

        // clear '//', '///' => '/'
        if (false !== \strpos($path, '//')) {
            $path = \preg_replace('#\/\/+#', '/', $path);
        }

        // must be start withs '/'
        if (\strpos($path, '/') !== 0) {
            $path = '/' . $path;
        }

        // decode
        $path = \rawurldecode($path);

        return $ignoreLastSlash ? \rtrim($path, '/') : $path;
    }

    /**
     * Get controller prefix for register route
     *
     * @param string $prefix Annotation controller prefix
     * @param string $class Controller class name
     * @param string $controllerSuffix Controller suffix
     *
     * @return string
     */
    public static function getControllerPrefix(string $prefix, string $class, string $controllerSuffix): string
    {
        if (empty($prefix)) {
            $regex  = '/^.*\\\(\w+)' . $controllerSuffix . '$/';

            if ($result = \preg_match($regex, $class, $match)) {
                $prefix = '/' . \lcfirst($match[1]);
            }
        }

        // always add '/' on start.
        return '/' . \trim($prefix, '/');
    }
}
