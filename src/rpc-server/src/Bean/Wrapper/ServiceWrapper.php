<?php

namespace Swoft\Rpc\Server\Bean\Wrapper;

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
use Swoft\Rpc\Server\Bean\Annotation\Service;

/**
 * Service eWrapper
 */
class ServiceWrapper extends AbstractWrapper
{
    /**
     * Class annotation
     *
     * @var array
     */
    protected $classAnnotations = [
        Service::class,
        Middleware::class,
        Middlewares::class,
    ];

    /**
     * Property annotations
     *
     * @var array
     */
    protected $propertyAnnotations = [
        Inject::class,
        Value::class,
    ];

    /**
     * Method annotation
     *
     * @var array
     */
    protected $methodAnnotations = [
        Middleware::class,
        Middlewares::class,
        Strings::class,
        Floats::class,
        Number::class,
        Integer::class,
        Enum::class
    ];

    /**
     * 是否解析类注解
     *
     * @param array $annotations
     *
     * @return bool
     */
    public function isParseClassAnnotations(array $annotations): bool
    {
        return isset($annotations[Service::class]);
    }

    /**
     * 是否解析属性注解
     *
     * @param array $annotations
     *
     * @return bool
     */
    public function isParsePropertyAnnotations(array $annotations): bool
    {
        return isset($annotations[Inject::class]) || isset($annotations[Value::class]);
    }

    /**
     * 是否解析方法注解
     *
     * @param array $annotations
     *
     * @return bool
     */
    public function isParseMethodAnnotations(array $annotations): bool
    {
        return true;
    }
}
