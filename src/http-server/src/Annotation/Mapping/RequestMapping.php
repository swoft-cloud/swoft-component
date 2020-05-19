<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Http\Server\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Required;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * HTTP action method annotation
 *
 * @Annotation
 * @Target("METHOD")
 *
 * @since 2.0
 */
final class RequestMapping
{
    // If route path:
    // - use "", eg: `@RequestMapping("")`
    // - or use "@prefix", eg: `@RequestMapping("@prefix")`
    // Will use the route prefix(Controller.prefix) as route.
    public const USE_PREFIX = '@prefix';

    /**
     * Action routing path
     *
     * @var string
     * @Required()
     */
    private $route = '';

    /**
     * Route name
     *
     * @var string
     */
    private $name = '';

    /**
     * Routing supported HTTP method set
     *
     * @var array
     */
    private $method = [RequestMethod::GET, RequestMethod::POST];

    /**
     * Routing path params binding. eg. {"id"="\d+"}
     *
     * @var array
     */
    private $params = [];

    /**
     * RequestMapping constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        $route = null;
        if (isset($values['value'])) {
            $route = (string)$values['value'];
        } elseif (isset($values['route'])) {
            $route = (string)$values['route'];
        }

        if (isset($route)) {
            // If use "", eg: `@RequestMapping("")`
            // Will use the route prefix as route.
            $this->route = $route === '' ? self::USE_PREFIX : $route;
        }

        if (isset($values['name'])) {
            $this->name = (string)$values['name'];
        }

        if (isset($values['method'])) {
            $this->method = (array)$values['method'];
        }

        if (isset($values['params'])) {
            $this->params = (array)$values['params'];
        }
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @return array
     */
    public function getMethod(): array
    {
        return $this->method;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
