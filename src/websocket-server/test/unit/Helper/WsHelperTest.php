<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\WebSocket\Server\Unit\Helper;

use PHPUnit\Framework\TestCase;
use Swoft\WebSocket\Server\Helper\WsHelper;

/**
 * Class WsHelperTest
 *
 * @since 2.0
 */
class WsHelperTest extends TestCase
{
    public function testGenSign(): void
    {
        $this->assertNotEmpty('/', WsHelper::genSign(''));
        $this->assertNotEmpty('/', WsHelper::genSign('abc'));
    }

    public function testIsInvalidSecKey(): void
    {
        $this->assertTrue(WsHelper::isInvalidSecKey(''));
    }
}
