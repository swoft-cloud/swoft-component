<?php
namespace SwoftTest;

use Swoft\Core\Coroutine;

/**
 * Class CorouotineTest
 *
 * @package Swoft\Test\Cases
 */
class CorouotineTest extends AbstractTestCase
{
    public function testIsSupportCoroutine()
    {
        $this->assertTrue(Coroutine::isSupportCoroutine());
    }

    public function testShouldWrapCoroutine()
    {
        $this->assertFalse(Coroutine::shouldWrapCoroutine());
    }
}