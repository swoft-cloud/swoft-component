<?php declare(strict_types=1);


namespace SwoftTest\Http\Server\Unit;


use Swoft\Bean\BeanFactory;
use Swoft\Test\Http\MockHttpServer;

/**
 * Class TestCase
 *
 * @since 2.0
 */
abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var MockHttpServer
     */
    protected $mockServer;

    /**
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function setUp()
    {
        $this->mockServer = BeanFactory::getBean(MockHttpServer::class);
    }
}