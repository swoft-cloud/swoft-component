<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Task\Unit;

use SwoftTest\Task\Testing\MockTaskServer;

/**
 * Class TestCase
 *
 * @since 2.0
 */
abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var MockTaskServer
     */
    protected $mockTaskServer;

    /**
     * Server
     */
    public function setUp(): void
    {
        $this->mockTaskServer = new MockTaskServer();
    }
}
