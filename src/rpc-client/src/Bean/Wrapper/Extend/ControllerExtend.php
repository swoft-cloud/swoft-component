<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Rpc\Client\Bean\Wrapper\Extend;

use Swoft\Bean\Wrapper\Extend\WrapperExtendInterface;
use Swoft\Rpc\Client\Bean\Annotation\Reference;

/**
 * Controller extend
 */
class ControllerExtend implements WrapperExtendInterface
{
    /**
     * @return array
     */
    public function getClassAnnotations(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function getPropertyAnnotations(): array
    {
        return [Reference::class,];
    }

    /**
     * @return array
     */
    public function getMethodAnnotations(): array
    {
        return [];
    }

    /**
     * @param array $annotations
     *
     * @return bool
     */
    public function isParseClassAnnotations(array $annotations): bool
    {
        return false;
    }

    /**
     * @param array $annotations
     *
     * @return bool
     */
    public function isParsePropertyAnnotations(array $annotations): bool
    {
        return isset($annotations[Reference::class]);
    }

    /**
     * @param array $annotations
     *
     * @return bool
     */
    public function isParseMethodAnnotations(array $annotations): bool
    {
        return false;
    }
}
