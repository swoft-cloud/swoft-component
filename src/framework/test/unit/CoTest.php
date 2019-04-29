<?php declare(strict_types=1);


namespace SwoftTest\Unit;


use Swoft\Co;
use Swoft\Context\Context;
use SwoftTest\Db\Unit\TestCase;
use Swoole\Coroutine\Http\Client;

/**
 * Class CoTest
 *
 * @since 2.0
 */
class CoTest extends TestCase
{
    /**
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
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

        Context::getWaitGroup()->wait();
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