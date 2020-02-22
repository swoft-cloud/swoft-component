<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Tcp\Server\Unit;

use Swoft\Tcp\Server\Connection;

/**
 * Class ConnectionTest
 */
class ConnectionTest extends TcpServerTestCase
{
    public function testConnection(): void
    {
        $info = [
            'reactor_id'   => 1,
            'server_fd'    => 1,
            'server_port'  => 9501,
            'remote_port'  => 19501,
            'remote_ip'    => '127.0.0.1',
            'connect_time' => 1390212495,
        ];
        $conn = Connection::new(1, $info);

        $this->assertSame(1, $conn->getFd());
        $this->assertIsArray($meta = $conn->getMetadata());
        $this->assertSame($info['connect_time'], $meta['connectTime']);
        $this->assertSame($info['remote_port'], $meta['port']);
        $this->assertSame($info['remote_port'], $conn->getMetaValue('port'));

        $this->assertNotEmpty($arr = $conn->toArray());
        $this->assertSame(1, $arr['fd']);
        $this->assertNotEmpty($str = $conn->toString());
        $this->assertStringContainsString('127.0.0.1', $str);

        // restore from metadata
        $conn1 = Connection::newFromArray($meta);
        $this->assertSame($conn1->getFd(), $conn->getFd());

        $this->assertNotEmpty($conn->getData());
        $conn->clear();
        $this->assertEmpty($conn->getData());
    }
}
