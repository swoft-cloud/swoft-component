<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Aop\Bean\Wrapper;

use Swoft\Aop\Bean\Annotation\After;
use Swoft\Aop\Bean\Annotation\AfterReturning;
use Swoft\Aop\Bean\Annotation\AfterThrowing;
use Swoft\Aop\Bean\Annotation\Around;
use Swoft\Aop\Bean\Annotation\Aspect;
use Swoft\Aop\Bean\Annotation\Before;
use Swoft\Aop\Bean\Annotation\Inject;
use Swoft\Aop\Bean\Annotation\PointAnnotation;
use Swoft\Aop\Bean\Annotation\PointBean;
use Swoft\Aop\Bean\Annotation\PointExecution;
use Swoft\Bean\Wrapper\AbstractWrapper;

/**
 * Class AspectWrapper
 *
 * @package Swoft\Aop\Bean\Wrapper
 */
class AspectWrapper extends AbstractWrapper
{
    /**
     * 类注解
     *
     * @var array
     */
    protected $classAnnotations = [
        Aspect::class,
        PointBean::class,
        PointAnnotation::class,
        PointExecution::class,
    ];

    /**
     * 属性注解
     *
     * @var array
     */
    protected $propertyAnnotations = [
        Inject::class,
    ];

    /**
     * 方法注解
     *
     * @var array
     */
    protected $methodAnnotations = [
        Before::class,
        After::class,
        AfterReturning::class,
        AfterThrowing::class,
        Around::class,
    ];

    /**
     * 是否解析类注解
     *
     * @param array $annotations
     * @return bool
     */
    public function isParseClassAnnotations(array $annotations): bool
    {
        return isset($annotations[Aspect::class]);
    }

    /**
     * 是否解析属性注解
     *
     * @param array $annotations
     * @return bool
     */
    public function isParsePropertyAnnotations(array $annotations): bool
    {
        return isset($annotations[Inject::class]);
    }

    /**
     * 是否解析方法注解
     *
     * @param array $annotations
     * @return bool
     */
    public function isParseMethodAnnotations(array $annotations): bool
    {
        $after = isset($annotations[After::class]) || isset($annotations[AfterThrowing::class]) || isset($annotations[AfterReturning::class]);

        return isset($annotations[Before::class]) || isset($annotations[Around::class]) || $after;
    }
}
