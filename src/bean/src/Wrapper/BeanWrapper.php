<?php

namespace Swoft\Bean\Wrapper;

use Swoft\Bean\Annotation\Bean;
use Swoft\Bean\Annotation\Cacheable;
use Swoft\Bean\Annotation\CachePut;
use Swoft\Bean\Annotation\Inject;
use Swoft\Bean\Annotation\Value;

class BeanWrapper extends AbstractWrapper
{
    protected $classAnnotations
        = [
            Bean::class
        ];

    protected $propertyAnnotations
        = [
            Inject::class,
            Value::class,
        ];

    protected $methodAnnotations = [
        Cacheable::class,
        CachePut::class,
    ];

    /**
     * 是否解析类注解
     */
    public function isParseClassAnnotations(array $annotations): bool
    {
        return isset($annotations[Bean::class]);
    }

    /**
     * 是否解析属性注解
     */
    public function isParsePropertyAnnotations(array $annotations): bool
    {
        return isset($annotations[Inject::class]) || isset($annotations[Value::class]);
    }

    /**
     * 是否解析方法注解
     */
    public function isParseMethodAnnotations(array $annotations): bool
    {
        return true;
    }

    protected function inMethodAnnotations($methodAnnotation): bool
    {
        return true;
    }
}
