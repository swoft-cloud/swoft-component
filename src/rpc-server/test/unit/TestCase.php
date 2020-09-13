<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Rpc\Server\Unit;

use SwoftTest\Rpc\Server\Testing\MockRpcServer;

/**
 * Class TestCase
 *
 * @since 2.0
 */
abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var MockRpcServer
     */
    protected $mockRpcServer;

    /**
     * Set up
     */
    public function setUp(): void
    {
        $this->mockRpcServer = new MockRpcServer();
    }
}
