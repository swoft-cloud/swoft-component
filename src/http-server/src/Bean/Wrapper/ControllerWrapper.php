<?php

namespace Swoft\Http\Server\Bean\Wrapper;

use Swoft\Bean\Annotation\Enum;
use Swoft\Bean\Annotation\Floats;
use Swoft\Bean\Annotation\Inject;
use Swoft\Bean\Annotation\Integer;
use Swoft\Http\Message\Bean\Annotation\Middleware;
use Swoft\Http\Message\Bean\Annotation\Middlewares;
use Swoft\Bean\Annotation\Number;
use Swoft\Bean\Annotation\Strings;
use Swoft\Bean\Annotation\Value;
use Swoft\Bean\Wrapper\AbstractWrapper;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;

/**
 * 路由注解封装器
 *
 * Class ControllerWrapper
 * @author    stelin <phpcrazy@126.com>
 */
class ControllerWrapper extends AbstractWrapper
{
    /**
     * @var array
     */
    protected $classAnnotations = [
        Controller::class,
        Middlewares::class,
        Middleware::class,
    ];

    /**
     * @var array
     */
    protected $propertyAnnotations = [
        Inject::class,
        Value::class,
    ];

    /**
     * @var array
     */
    protected $methodAnnotations = [
        RequestMapping::class,
        Middlewares::class,
        Middleware::class,
        Strings::class,
        Floats::class,
        Number::class,
        Integer::class,
        Enum::class
    ];

    /**
     * @param array $annotations
     * @return bool
     */
    public function isParseClassAnnotations(array $annotations): bool
    {
        return isset($annotations[Controller::class]);
    }

    /**
     * @param array $annotations
     * @return bool
     */
    public function isParsePropertyAnnotations(array $annotations): bool
    {
        return isset($annotations[Inject::class]) || isset($annotations[Value::class]);
    }

    /**
     * @param array $annotations
     * @return bool
     */
    public function isParseMethodAnnotations(array $annotations): bool
    {
        return true;
    }
}
