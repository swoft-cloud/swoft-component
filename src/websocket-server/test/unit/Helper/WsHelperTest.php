<?php declare(strict_types=1);

namespace SwoftTest\WebSocket\Server\Unit\Helper;

use Swoft\Test\BaseTestCase;
use Swoft\WebSocket\Server\Helper\WsHelper;

/**
 * Class WsHelperTest
 *
 * @since 2.0
 */
class WsHelperTest extends BaseTestCase
{
    public function testFormatPath(): void
    {
        $this->assertSame('/', WsHelper::formatPath(''));
        $this->assertSame('/a', WsHelper::formatPath('a'));
        $this->assertSame('/a', WsHelper::formatPath('a/'));
        $this->assertSame('/a', WsHelper::formatPath('/a/'));
    }
}
