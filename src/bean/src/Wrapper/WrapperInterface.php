<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Bean\Wrapper;

use Swoft\Bean\Wrapper\Extend\WrapperExtendInterface;

/**
 * Annotation Wrapper Interface
 */
interface WrapperInterface
{
    /**
     * 封装注解
     */
    public function doWrapper(string $className, array $annotations): array;

    /**
     * 是否解析类注解
     */
    public function isParseClassAnnotations(array $annotations): bool;

    /**
     * 是否解析属性注解
     */
    public function isParsePropertyAnnotations(array $annotations): bool;

    /**
     * 是否解析方法注解
     */
    public function isParseMethodAnnotations(array $annotations): bool;

    public function addExtends(WrapperExtendInterface $extend);
}
