<?php
declare(strict_types=1);
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
use Swoft\Bean\Annotation\Pool;
use Swoft\Bean\Annotation\Value;

class PoolWrapper extends AbstractWrapper
{
    protected $classAnnotations = [
        Pool::class,
    ];

    protected $propertyAnnotations = [
        Inject::class,
        Value::class,
    ];

    protected $methodAnnotations = [];

    public function isParseClassAnnotations(array $annotations): bool
    {
        return isset($annotations[Pool::class]);
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
