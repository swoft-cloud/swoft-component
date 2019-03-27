<?php
if (!function_exists('env')) {
    /**
     * Gets the value of an environment variable.
     *
     * @param  string $key
     * @param  mixed  $default
     *
     * @return mixed
     */
    function env($key, $default = null)
    {
        $value = getenv($key);

        if ($value === false) {
            return \value($default);
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
                return null;
        }

        if (defined($value)) {
            $value = constant($value);
        }

        return $value;
    }
}

if (!function_exists('alias')) {
    /**
     * @param string $key
     *
     * @return string
     */
    function alias(string $key): string
    {
        return \Swoft::getAlias($key);
    }
}

if (!function_exists('event')) {
    /**
     * @return \Swoft\Event\Manager\EventManager
     * @throws ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    function event(): \Swoft\Event\Manager\EventManager
    {
        return \Swoft\Bean\BeanFactory::getBean('eventManager');
    }
}

if (!function_exists('config')) {
    /**
     * Get value from config by key or default
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    function config(string $key, $default = null)
    {
        if (!\Swoft\Bean\BeanFactory::hasBean('config')) {
            return sprintf('${%s}', $key);
        }

        /* @var \Swoft\Config\Config $config */
        $config = \Swoft\Bean\Container::$instance->getSingleton('config');

        return $config->get($key, $default);
    }
}

if (!function_exists('sgo')) {
    /**
     * Create coroutine like 'go()'
     * In the swoft, you must use `sgo()` instead of  swoole `go()` function
     *
     * @param callable $callable
     * @param bool $wait
     */
    function sgo(callable $callable, bool $wait = true)
    {
        \Swoft\Co::create($callable, $wait);
    }
}

if (!function_exists('context')) {
    /**
     * Get current context
     *
     * @return \Swoft\Context\ContextInterface|\Swoft\Http\Server\HttpContext
     */
    function context(): \Swoft\Context\ContextInterface
    {
        return \Swoft\Context\Context::get();
    }
}

if (!function_exists('server')) {
    /**
     * Get server instance
     *
     * @return \Swoft\Server\Server|\Swoft\Http\Server\HttpServer|\Swoft\WebSocket\Server\WebSocketServer
     */
    function server(): \Swoft\Server\Server
    {
        return \Swoft\Server\Server::getServer();
    }
}
