<?php declare(strict_types=1);


namespace SwoftTest\Unit;


use PHPUnit\Framework\TestCase;
use ReflectionException;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Co;
use Swoft\Context\Context;
use Swoole\Coroutine\Http\Client;

/**
 * Class CoTest
 *
 * @since 2.0
 */
class CoTest extends TestCase
{
    /**
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function tearDown()
    {
        Context::getWaitGroup()->wait();
    }

    /**
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function testMulti()
    {
        $requests = [
            'method'       => [$this, 'requestMethod'],
            'staticMethod' => "SwoftTest\Unit\CoTest::requestMehtodByStatic",
            'closure'      => function () {
                $cli = new Client('www.baidu.com', 80);
                $cli->get('/');
                $result = $cli->body;
                $cli->close();

                return $result;
            }
        ];

        $response = Co::multi($requests);

        $this->assertEquals(\count($response), 3);
    }

    /**
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function testMulti2()
    {
        $requests = [
            'closure'      => function () {
                $cli = new Client('192.1.1.1', 80);
                $cli->get('/');
                $result = $cli->body;
                $cli->close();

                return $result;
            },
            'closure2'      => function () {
                $cli = new Client('192.1.1.1', 80);
                $cli->get('/');
                $result = $cli->body;
                $cli->close();

                return $result;
            }
        ];

        $response = Co::multi($requests, 1);

        $this->assertTrue(empty($response));
    }

    /**
     * @return mixed
     */
    public function requestMethod()
    {
        $cli = new Client('www.baidu.com', 80);
        $cli->get('/');
        $result = $cli->body;
        $cli->close();

        return $result;
    }

    /**
     * @return mixed
     */
    public static function requestMehtodByStatic()
    {
        $cli = new Client('www.baidu.com', 80);
        $cli->get('/');
        $result = $cli->body;
        $cli->close();

        return $result;
    }
}