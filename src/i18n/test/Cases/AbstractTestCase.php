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

namespace SwoftTest\I18n\Cases;

use PHPUnit\Framework\TestCase;

abstract class AbstractTestCase extends TestCase
{
    /**
     * @param callable $func
     * @param          $expectedExceptionClass
     */
    protected function assertException(callable $func, string $expectedExceptionClass)
    {
        try {
            value($func);
        } catch (\Exception $e) {
            $this->assertSame($expectedExceptionClass, \get_class($e));
        }
    }

    /**
     * @param callable $func
     * @param          $expectedErrorClass
     */
    protected function assertError(callable $func, string $expectedErrorClass)
    {
        try {
            value($func);
        } catch (\Throwable $t) {
            $this->assertSame($expectedErrorClass, \get_class($t));
        }
    }
}
