<?php

namespace Swoft\Http\Server\Router;

/**
 * Class AbstractRouter
 * @package Swoft\Http\Server\Router
 * @author inhere <in.798@qq.com>
 *
 * @method get(string $route, mixed $handler, array $opts = [])
 * @method post(string $route, mixed $handler, array $opts = [])
 * @method put(string $route, mixed $handler, array $opts = [])
 * @method delete(string $route, mixed $handler, array $opts = [])
 * @method options(string $route, mixed $handler, array $opts = [])
 * @method head(string $route, mixed $handler, array $opts = [])
 * @method search(string $route, mixed $handler, array $opts = [])
 * @method connect(string $route, mixed $handler, array $opts = [])
 * @method trace(string $route, mixed $handler, array $opts = [])
 * @method any(string $route, mixed $handler, array $opts = [])
 */
abstract class AbstractRouter implements RouterInterface
{
    /** @var string The router name */
    private $name = '';

    /**
     * some available patterns regex
     * $router->get('/user/{id}', 'handler');
     * @var array
     */
    protected static $globalParams = [
        'all' => '.*',
        'any' => '[^/]+',        // match any except '/'
        'num' => '[1-9][0-9]*',  // match a number and gt 0
        'int' => '\d+',          // match a number
        'act' => '[a-zA-Z][\w-]+', // match a action name
    ];

    /** @var bool */
    protected $initialized = false;

    /** @var string */
    protected $currentGroupPrefix;

    /** @var array */
    protected $currentGroupOption;

    /**
     * static Routes - no dynamic argument match
     * 整个路由 path 都是静态字符串 e.g. '/user/login'
     * @var array[]
     * [
     *     '/user/login' => [
     *          // METHOD => [...]
     *          'GET' => [
     *              'handler' => 'handler',
     *              'option' => [...],
     *          ],
     *          'PUT' => [
     *              'handler' => 'handler',
     *              'option' => [...],
     *          ],
     *          ...
     *      ],
     *      ... ...
     * ]
     */
    protected $staticRoutes = [];

    /**
     * regular Routes - have dynamic arguments, but the first node is normal string.
     * 第一节是个静态字符串，称之为有规律的动态路由。按第一节的信息进行分组存储
     * e.g '/hello/{name}' '/user/{id}'
     * @var array[]
     * [
     *     // 使用完整的第一节作为key进行分组
     *     'add' => [
     *          [
     *              'start' => '/add/',
     *              'regex' => '/add/(\w+)',
     *              'methods' => 'GET',
     *              'handler' => 'handler',
     *              'option' => [...],
     *          ],
     *          ...
     *      ],
     *     'blog' => [
     *        [
     *              'start' => '/blog/post-',
     *              'regex' => '/blog/post-(\w+)',
     *              'methods' => 'GET',
     *              'handler' => 'handler',
     *              'option' => [...],
     *        ],
     *        ...
     *     ],
     *     ... ...
     * ]
     */
    protected $regularRoutes = [];

    /**
     * vague Routes - have dynamic arguments,but the first node is exists regex.
     * 第一节就包含了正则匹配，称之为无规律/模糊的动态路由
     * e.g '/{name}/profile' '/{some}/{some2}'
     * @var array[]
     * [
     *     // 使用 HTTP METHOD 作为 key进行分组
     *     'GET' => [
     *          [
     *              // 开始的字符串
     *              'start' => '/profile',
     *              'regex' => '/(\w+)/profile',
     *              'handler' => 'handler',
     *              'option' => [...],
     *          ],
     *          ...
     *     ],
     *     'POST' => [
     *          [
     *              'start' => null,
     *              'regex' => '/(\w+)/(\w+)',
     *              'handler' => 'handler',
     *              'option' => [...],
     *          ],
     *          ...
     *     ],
     *      ... ...
     * ]
     */
    protected $vagueRoutes = [];

    /*******************************************************************************
     * router config
     ******************************************************************************/

    /**
     * Match all request.
     * 1. If is a valid URI path, will matchAll all request uri to the path.
     * 2. If is a closure, will matchAll all request then call it
     * eg: '/site/maintenance' or `function () { echo 'System Maintaining ... ...'; }`
     * @var mixed
     */
    public $matchAll;

    /**
     * Ignore last slash char('/'). If is True, will clear last '/'.
     * @var bool
     */
    public $ignoreLastSlash = false;

    /**
     * @var bool NotAllowed As NotFound. If True, only two status value will be return(FOUND, NOT_FOUND).
     */
    public $notAllowedAsNotFound = false;

    /**
     * object creator.
     * @param array $config
     * @return self
     * @throws \LogicException
     */
    public static function make(array $config = []): AbstractRouter
    {
        return new static($config);
    }

    /**
     * object constructor.
     * @param array $config
     * @throws \LogicException
     */
    public function __construct(array $config = [])
    {
        $this->setConfig($config);

        $this->currentGroupPrefix = '';
        $this->currentGroupOption = [];
    }

    /**
     * @param array $config
     * @throws \LogicException
     */
    public function setConfig(array $config)
    {
        if ($this->initialized) {
            throw new \LogicException('Routing has been added, and configuration is not allowed!');
        }

        $props = [
            'name' => 1,
            'defaultAction' => 1,
            'ignoreLastSlash' => 1,
            'tmpCacheNumber' => 1,
            'notAllowedAsNotFound' => 1,
            'matchAll' => 1,
        ];

        foreach ($config as $name => $value) {
            if (isset($props[$name])) {
                $this->$name = $value;
            }
        }
    }

    /*******************************************************************************
     * route collection
     ******************************************************************************/

    /**
     * Defines a route callback and method
     * @param string $method
     * @param array $args
     * @return static
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    public function __call($method, array $args)
    {
        if (\in_array(\strtoupper($method), self::ALLOWED_METHODS, true)) {
            if (\count($args) < 2) {
                throw new \InvalidArgumentException("The method [$method] parameters is missing.");
            }

            return $this->map($method, ...$args);
        }

        throw new \InvalidArgumentException("The method [$method] not exists in the class.");
    }

    /**
     * Create a route group with a common prefix.
     * All routes created in the passed callback will have the given group prefix prepended.
     * @ref package 'nikic/fast-route'
     * @param string $prefix
     * @param \Closure $callback
     * @param array $opts
     */
    public function group(string $prefix, \Closure $callback, array $opts = [])
    {
        $previousGroupPrefix = $this->currentGroupPrefix;
        $this->currentGroupPrefix = $previousGroupPrefix . '/' . trim($prefix, '/');

        $previousGroupOption = $this->currentGroupOption;
        $this->currentGroupOption = $opts;

        $callback($this);

        $this->currentGroupPrefix = $previousGroupPrefix;
        $this->currentGroupOption = $previousGroupOption;
    }

    /**
     * validate and format arguments
     * @param string|array $methods
     * @param mixed $handler
     * @return array
     * @throws \InvalidArgumentException
     */
    public function validateArguments($methods, $handler): array
    {
        if (!$methods || !$handler) {
            throw new \InvalidArgumentException('The method and route handler is not allow empty.');
        }

        if (\is_string($methods)) {
            $method = \strtoupper($methods);

            if ($method === 'ANY') {
                return self::ALLOWED_METHODS;
            }

            if (false === \strpos(self::ALLOWED_METHODS_STR . ',', $method . ',')) {
                throw new \InvalidArgumentException(
                    "The method [$method] is not supported, Allow: " . self::ALLOWED_METHODS_STR
                );
            }

            return [$method];
        }

        $upperMethods = [];

        foreach ((array)$methods as $method) {
            $method = \strtoupper($method);

            if ($method === 'ANY') {
                return self::ALLOWED_METHODS;
            }

            if (false === \strpos(self::ALLOWED_METHODS_STR . ',', $method . ',')) {
                throw new \InvalidArgumentException(
                    "The method [$method] is not supported, Allow: " . self::ALLOWED_METHODS_STR
                );
            }

            $upperMethods[] = $method;
        }

        return $upperMethods;
    }

    /**
     * parse param route
     * @param array $params
     * @param array $conf
     * @return array
     * @throws \LogicException
     */
    public function parseParamRoute(array $conf, array $params = []): array
    {
        $first = null;
        $backup = $route = $conf['original'];
        $argPos = \strpos($route, '{');

        // quote '.','/' to '\.','\/'
        if (false !== \strpos($route, '.')) {
            // $route = preg_quote($route, '/');
            $route = \str_replace('.', '\.', $route);
        }

        // Parse the optional parameters
        if (false !== ($optPos = \strpos($route, '['))) {
            $withoutClosingOptionals = \rtrim($route, ']');
            $optionalNum = \strlen($route) - \strlen($withoutClosingOptionals);

            if ($optionalNum !== \substr_count($withoutClosingOptionals, '[')) {
                throw new \LogicException('Optional segments can only occur at the end of a route');
            }

            // '/hello[/{name}]' -> '/hello(?:/{name})?'
            $route = \str_replace(['[', ']'], ['(?:', ')?'], $route);

            // no params
            if ($argPos === false) {
                $noOptional = \substr($route, 0, $optPos);
                $conf['start'] = $noOptional;
                $conf['regex'] = '#^' . $route . '$#';

                // eg '/article/12'
                if ($pos = \strpos($noOptional, '/', 1)) {
                    $first = \substr($noOptional, 1, $pos - 1);
                }

                return [$first, $conf];
            }

            $floorPos = $argPos >= $optPos ? $optPos : $argPos;
        } else {
            $floorPos = (int)$argPos;
        }

        $start = \substr($backup, 0, $floorPos);

        // Parse the parameters and replace them with the corresponding regular
        if (\preg_match_all('#\{([a-zA-Z_][\w-]*)\}#', $route, $m)) {
            /** @var array[] $m */
            $pairs = [];

            foreach ($m[1] as $name) {
                $regex = $params[$name] ?? self::DEFAULT_REGEX;
                $pairs['{' . $name . '}'] = '(' . $regex . ')';
            }

            $route = \strtr($route, $pairs);
            $conf['matches'] = $m[1];
        }

        $conf['regex'] = '#^' . $route . '$#';
        $conf['start'] = $start === '/' ? null : $start;

        // regular: first node is a normal string e.g '/user/{id}' -> 'user', '/a/{post}' -> 'a'
        if ($pos = \strpos($start, '/', 1)) {
            $first = \substr($start, 1, $pos - 1);
        }

        return [$first, $conf];
    }

    /**
     * @param array $matches
     * @param array[] $conf
     * @return array
     */
    protected function mergeMatches(array $matches, array $conf): array
    {
        if (!$matches || !isset($conf['matches'])) {
            $conf['matches'] = $conf['option']['defaults'] ?? [];

            return $conf;
        }

        // first is full match.
        \array_shift($matches);

        $newMatches = [];

        foreach ($conf['matches'] as $k => $name) {
            if (isset($matches[$k])) {
                $newMatches[$name] = $matches[$k];
            }
        }

        // apply some default param value
        if (isset($conf['option']['defaults'])) {
            $conf['matches'] = \array_merge($conf['option']['defaults'], $newMatches);
        } else {
            $conf['matches'] = $newMatches;
        }

        return $conf;
    }

    /**
     * @param string $first
     * @param string $path
     * @param string $method
     * @return array
     */
    abstract protected function findInRegularRoutes(string $first, string $path, string $method): array;

    /**
     * @param string $path
     * @param string $method
     * @return array|false
     */
    abstract protected function findInVagueRoutes(string $path, string $method);

    /**
     * is Static Route
     * @param string $route
     * @return bool
     */
    public static function isStaticRoute(string $route): bool
    {
        return \strpos($route, '{') === false && \strpos($route, '[') === false;
    }

    /**
     * @param array $tmpParams
     * @return array
     */
    public function getAvailableParams(array $tmpParams): array
    {
        $params = self::$globalParams;

        if ($tmpParams) {
            $params = \array_merge($params, $tmpParams);
        }

        return $params;
    }

    /**
     * @param array $params
     */
    public function addGlobalParams(array $params)
    {
        foreach ($params as $name => $pattern) {
            $this->addGlobalParam($name, $pattern);
        }
    }

    /**
     * @param $name
     * @param $pattern
     */
    public function addGlobalParam($name, $pattern)
    {
        $name = \trim($name, '{} ');
        self::$globalParams[$name] = $pattern;
    }

    /**
     * @return array
     */
    public static function getGlobalParams(): array
    {
        return self::$globalParams;
    }

    /**
     * @return array
     */
    public static function getSupportedMethods(): array
    {
        return self::ALLOWED_METHODS;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param array $staticRoutes
     */
    public function setStaticRoutes(array $staticRoutes)
    {
        $this->staticRoutes = $staticRoutes;
    }

    /**
     * @return array
     */
    public function getStaticRoutes(): array
    {
        return $this->staticRoutes;
    }

    /**
     * @param \array[] $regularRoutes
     */
    public function setRegularRoutes(array $regularRoutes)
    {
        $this->regularRoutes = $regularRoutes;
    }

    /**
     * @return \array[]
     */
    public function getRegularRoutes(): array
    {
        return $this->regularRoutes;
    }

    /**
     * @param array $vagueRoutes
     */
    public function setVagueRoutes(array $vagueRoutes)
    {
        $this->vagueRoutes = $vagueRoutes;
    }

    /**
     * @return array
     */
    public function getVagueRoutes(): array
    {
        return $this->vagueRoutes;
    }
}
