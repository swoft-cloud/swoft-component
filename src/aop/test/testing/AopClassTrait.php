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

trait AopClassTrait
{
    public function traitMethod(int $int, string $str, float $float, AopClass $aopClass): int
    {
        return 0;
    }

    public function traitMethodSelf(int $int, string $str, float $float, AopClass $aopClass): self
    {
        return self;
    }

    public function traitMethodVoid(int $int, $str, $float, $aopClass): void
    {
    }

    public function traitMethodNull($int, $str, float $float, AopClass $aopClass): void
    {
    }
}
