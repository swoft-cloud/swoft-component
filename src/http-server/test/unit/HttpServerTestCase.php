<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Http\Server\Unit;

use Swoft\Bean\BeanFactory;
use SwoftTest\Http\Server\Testing\MockHttpServer;

/**
 * Class TestCase
 *
 * @since 2.0
 */
abstract class HttpServerTestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var MockHttpServer
     */
    protected $mockServer;

    public function setUp(): void
    {
        $this->mockServer = BeanFactory::getBean(MockHttpServer::class);
    }
}
