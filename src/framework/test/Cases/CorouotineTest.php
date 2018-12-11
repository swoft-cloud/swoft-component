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
namespace SwoftTest\Cases;

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
