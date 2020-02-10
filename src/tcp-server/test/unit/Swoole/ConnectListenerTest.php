<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Tcp\Server\Unit\Swoole;

use Swoft\Tcp\Server\Swoole\ConnectListener;
use SwoftTest\Tcp\Server\Unit\TcpServerTestCase;

/**
 * Class ConnectListenerTest
 */
class ConnectListenerTest extends TcpServerTestCase
{
    /**
     */
    public function testOnConnect(): void
    {
        // Ensure is empty
        // TempString::clear();

        $cl = new ConnectListener();
        // $cl->onConnect($this->swServer(), 1, 1);

        $this->assertNotEmpty($cl);
    }
}
