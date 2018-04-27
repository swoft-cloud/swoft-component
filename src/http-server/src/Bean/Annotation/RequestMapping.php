<?php

namespace Swoft\Http\Server\Bean\Annotation;

/**
 * Action method annotation label
 *
 * @Annotation
 * @Target("METHOD")
 */
class RequestMapping
{
    /**
     * action route path
     * @var string
     */
    private $route = '';

    /**
     * allowed methods
     * @var array
     */
    private $method = [RequestMethod::GET, RequestMethod::POST];

    /**
     * route param define. eg {"id"="\d+"}
     * @var array
     */
    private $params = [];

    /**
     * params default values. eg {"id"=12}
     * @var array
     */
    private $defaults = [];

    /**
     * RequestMapping constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->route = $values['value'];
        }

        if (isset($values['route'])) {
            $this->route = $values['route'];
        }

        if (isset($values['method'])) {
            $method = $values['method'];
            $this->method = (array)$method;
        }

        if (isset($values['params'])) {
            $this->params = $values['params'];
        }

        if (isset($values['defaults'])) {
            $this->defaults = $values['defaults'];
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
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     * @return array
     */
    public function getDefaults(): array
    {
        return $this->defaults;
    }

    /**
     * @param array $defaults
     */
    public function setDefaults(array $defaults)
    {
        $this->defaults = $defaults;
    }
}
