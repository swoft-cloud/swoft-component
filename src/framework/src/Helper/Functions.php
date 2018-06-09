<?php
/**
 * @version   2017-11-02
 * @author    huangzhhui <huangzhwork@gmail.com>
 * @copyright Copyright 2010-2017 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */

use Swoft\Core\RequestContext;
use Swoft\App;

if (! function_exists('value')) {
    /**
     * Return the callback value
     *
     * @param mixed $value
     * @return mixed
     */
    function value($value)
    {
        return $value instanceof \Closure ? $value() : $value;
    }
}

if (! function_exists('env')) {
    /**
     * Gets the value of an environment variable.
     *
     * @param  string $key
     * @param  mixed  $default
     * @return mixed
     */
    function env($key, $default = null)
    {
        $value = getenv($key);

        if ($value === false) {
            return value($default);
        }

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return;
        }

        if (strlen($value) > 1 && \Swoft\Helper\StringHelper::startsWith($value, '"') && \Swoft\Helper\StringHelper::endsWith($value, '"')) {
            return substr($value, 1, -1);
        }

        if (defined($value)) {
            $value = constant($value);
        }

        return $value;
    }
}

if (! function_exists('cache')) {

    /**
     * Get the cache by $key value, return $default when cache not exist
     *
     * @param string|null $key
     * @param mixed       $default
     * @return \Psr\SimpleCache\CacheInterface|string
     */
    function cache(string $key = null, $default = null)
    {
        /* @var Swoft\Cache\Cache $cache */
        $cache = App::getBean('cache');

        if ($key === null) {
            return $cache->getDriver();
        }

        return $cache->get($key, value($default));
    }
}

if (! function_exists('bean')) {
    /**
     * Get bean from container
     *
     * @param string $name
     * @return object
     */
    function bean(string $name)
    {
        return App::getBean($name);
    }
}

if (! function_exists('config')) {
    /**
     * Get config value from app config
     *
     * @param string     $key
     * @param null|mixed $default
     * @return mixed
     */
    function config(string $key, $default = null)
    {
        /** @see \Swoft\Core\Config::get() */
        return App::getBean('config')->get($key, $default);
    }
}

if (! function_exists('alias')) {
    /**
     * Get alias
     *
     * @param string $alias
     * @return string
     * @throws \InvalidArgumentException
     */
    function alias(string $alias): string
    {
        return App::getAlias($alias);
    }
}

if (! function_exists('request')) {
    /**
     * Get the current Request object from RequestContext
     *
     * @return \Psr\Http\Message\RequestInterface|Swoft\Http\Message\Server\Request
     */
    function request(): \Psr\Http\Message\RequestInterface
    {
        return RequestContext::getRequest();
    }
}

if (! function_exists('response')) {
    /**
     * Get the current Response object from RequestContext
     *
     * @return \Psr\Http\Message\ResponseInterface|\Swoft\Http\Message\Server\Response
     */
    function response(): \Psr\Http\Message\ResponseInterface
    {
        return RequestContext::getResponse();
    }
}

if (! function_exists('defer')) {
    /**
     * Push a callable to defer stack
     *
     * @param callable $value
     * @param array    $parameters
     * @return \Swoft\Defer\Defer
     */
    function defer(callable $value, array $parameters = [])
    {
        /** @var Swoft\Defer\Defer $defer */
        $defer = RequestContext::get('defer', new Swoft\Defer\Defer(), true);
        return $defer->push($value, $parameters);
    }
}

if (! function_exists('cookie')) {
    /**
     * @throws \RuntimeException
     * @return \Swoft\Http\Message\Cookie\CookieManager
     */
    function cookie()
    {
        if (! class_exists(\Swoft\Http\Message\Cookie\CookieManager::class)) {
            throw new \RuntimeException('CookieManager not exist, Did you forget to require swoft\http-message ?');
        }
        $manager = RequestContext::get('cookie', value(function () {
            /** @var \Swoft\Http\Message\Cookie\CookieManager $manager */
            $manager = \bean(\Swoft\Http\Message\Cookie\CookieManager::class);
            $request = RequestContext::getRequest();
            if (! $request instanceof \Psr\Http\Message\RequestInterface) {
                throw new \RuntimeException('Note that cookie() function only run in HTTP context');
            }
            $manager->setRequest($request);
            return $manager;
        }), true);
        return $manager;
    }
}

if (!function_exists('is_iterable')) {
    /**
     * is_iterable
     * @param $obj
     * @return bool
     */
    function is_iterable($obj)
    {
        return is_array($obj) || (is_object($obj) && ($obj instanceof \Traversable));
    }
}
