<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Aop\Testing;

class AopClass
{
    use AopClassTrait;

    public function Method(int $int, string $str, float $float, AopClass $aopClass): int
    {
        return 0;
    }

    public function MethodSelf(int $int, string $str, float $float, AopClass $aopClass): self
    {
        return self;
    }

    public function MethodVoid(int $int, string $str, float $float, AopClass $aopClass): void
    {
    }

    public function MethodNull($int, $str, $float, $aopClass): void
    {
    }
}
