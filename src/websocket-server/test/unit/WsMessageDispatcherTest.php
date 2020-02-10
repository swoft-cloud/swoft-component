<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\WebSocket\Server\Unit;

use PHPUnit\Framework\TestCase;
use Swoft\WebSocket\Server\WsMessageDispatcher;

/**
 * Class WsMessageDispatcherTest
 */
class WsMessageDispatcherTest extends TestCase
{
    /**
     */
    public function testBasic(): void
    {
        /** @var WsMessageDispatcher $wmd */
        $wmd = bean('wsMsgDispatcher');
        $this->assertNotEmpty($wmd);
    }

    public function testDispatch(): void
    {
        $wmd = bean('wsMsgDispatcher');
        $this->assertNotEmpty($wmd);
    }

    /**
     */
    public function testCustomDispatch(): void
    {
        $wmd = bean('wsMsgDispatcher');
        $this->assertNotEmpty($wmd);
    }
}
