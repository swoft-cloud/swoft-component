<?php
/**
 * Created by PhpStorm.
 * User: zcm
 * Date: 2018/7/23
 * Time: 10:55
 */

namespace Swoft\Encrypt\Bean\Wrapper;

use Swoft\Encrypt\Bean\Annotation\Encrypt;
use Swoft\Bean\Wrapper\AbstractWrapper;

/**
 * Class EncryptWrapper
 * @package Swoft\Encrypt\Bean\Wrapper
 */
class EncryptWrapper extends AbstractWrapper
{
    /**
     * 解析哪些类注解
     * @var array
     */
    protected $classAnnotations = [
        Encrypt::class,
    ];

    /**
     * 解析哪些方法注解
     * @var array
     */
    protected $methodAnnotations = [
        Encrypt::class,
    ];

    /**
     * 是否解析类注解
     * @param array $annotations
     * @return bool
     */
    public function isParseClassAnnotations(array $annotations): bool
    {
        return true;
    }

    /**
     * 是否解析属性注解
     * @param array $annotations
     * @return bool
     */
    public function isParsePropertyAnnotations(array $annotations): bool
    {
        return false;
    }

    /**
     * 是否解析方法注解
     * @param array $annotations
     * @return bool
     */
    public function isParseMethodAnnotations(array $annotations): bool
    {
        return true;
    }

}