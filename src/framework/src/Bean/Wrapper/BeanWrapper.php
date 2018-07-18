<?php

namespace Swoft\Bean\Wrapper;

use Swoft\Bean\Annotation\Bean;
use Swoft\Bean\Annotation\Cacheable;
use Swoft\Bean\Annotation\CachePut;
use Swoft\Bean\Annotation\CustomMethod;
use Swoft\Bean\Annotation\Inject;
use Swoft\Bean\Annotation\Value;

/**
 * Bean封装器
 *
 * @uses      BeanWrapper
 * @version   2017年09月05日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class BeanWrapper extends AbstractWrapper
{
    /**
     * 类注解
     *
     * @var array
     */
    protected $classAnnotations
        = [
            Bean::class
        ];

    /**
     * 属性注解
     *
     * @var array
     */
    protected $propertyAnnotations
        = [
            Inject::class,
            Value::class,
        ];

    /**
     * the annotations of method
     *
     * @var array
     */
    protected $methodAnnotations = [
        Cacheable::class,
        CachePut::class,
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
        return isset($annotations[Bean::class]);
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
        foreach ($annotations as $key => $annotation) {
            // 当注解时默认的 Cacheable 或者 CachePut 时，则为true
            if (in_array($key, $this->methodAnnotations)) {
                return true;
            }
            foreach ($annotation as $object) {
                // 当注解继承了 CustomMethod 时，则为true
                if ($object instanceof CustomMethod) {
                    return true;
                }
            }
        }
        return false;
    }

    protected function inMethodAnnotations($methodAnnotation): bool
    {
        if (parent::inMethodAnnotations($methodAnnotation)) {
            return true;
        }

        if ($methodAnnotation instanceof CustomMethod) {
            return true;
        }

        return false;
    }
}
