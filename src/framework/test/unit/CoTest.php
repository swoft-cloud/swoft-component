<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Unit;

use Exception;
use PHPUnit\Framework\TestCase;
use Swoft\Co;
use Swoft\Context\Context;
use Swoole\Coroutine\Http\Client;
use function count;

/**
 * Class CoTest
 *
 * @since 2.0
 */
class CoTest extends TestCase
{
    /**
     */
    public function tearDown()
    {
        Context::getWaitGroup()->wait();
    }

    /**
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
            },
            'exception'    => [$this, 'exceptionMethod']
        ];

        $response = Co::multi($requests);

        $this->assertEquals(count($response), 3);
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
     * @throws Exception
     */
    public function exceptionMethod()
    {
        throw new Exception('xx exception');
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
