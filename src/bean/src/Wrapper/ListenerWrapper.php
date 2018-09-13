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

use Swoft\Bean\Annotation\Inject;
use Swoft\Bean\Annotation\Listener;
use Swoft\Bean\Annotation\Value;

class ListenerWrapper extends AbstractWrapper
{
    protected $classAnnotations = [
        Listener::class
    ];

    protected $propertyAnnotations = [
        Inject::class,
        Value::class,
    ];

    public function isParseClassAnnotations(array $annotations): bool
    {
        return isset($annotations[Listener::class]);
    }

    public function isParsePropertyAnnotations(array $annotations): bool
    {
        return isset($annotations[Inject::class]) || isset($annotations[Value::class]);
    }

    public function isParseMethodAnnotations(array $annotations): bool
    {
        return false;
    }
}
