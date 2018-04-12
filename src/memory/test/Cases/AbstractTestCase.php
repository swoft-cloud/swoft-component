<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Memory;

use PHPUnit\Framework\TestCase;

abstract class AbstractTestCase extends TestCase
{

    /**
     * @param callable $func
     * @param string   $expectedExceptionClass
     */
    protected function assertException(callable $func, string $expectedExceptionClass)
    {
        try {
            value($func);
            throw new \RuntimeException('No expected exception');
        } catch (\Exception $e) {
            $this->assertEquals($expectedExceptionClass, \get_class($e));
        }
    }

    /**
     * @param array  $funcs
     * @param string $expectedExceptionClass
     */
    protected function assertExceptionMulti(array $funcs, string $expectedExceptionClass)
    {
        foreach ($funcs as $func) {
            try {
                value($func);
                throw new \RuntimeException('No expected exception');
            } catch (\Exception $e) {
                $this->assertEquals($expectedExceptionClass, \get_class($e));
            }
        }
    }

    /**
     * @param callable $func
     * @param string   $expectedErrorClass
     */
    protected function assertError(callable $func, string $expectedErrorClass)
    {
        try {
            value($func);
            throw new \RuntimeException('No expected error');
        } catch (\Throwable $t) {
            $this->assertEquals($expectedErrorClass, \get_class($t));
        }
    }
}
