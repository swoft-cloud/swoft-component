<?php
/**
 * Created by PhpStorm.
 * User: zcm
 * Date: 2018/8/8
 * Time: 17:13
 */

namespace SwoftTest\Encrypt\Handler;

use Swoft\App;
use Swoft\Encrypt\Handler\EncryptHandler;
use Swoft\Http\Message\Server\Request;
use Swoft\Http\Server\Router\HandlerMapping;
use SwoftTest\Encrypt\AbstractTestCase;

class EncryptHandlerTest extends AbstractTestCase
{
    protected $result = ['name' => '小红', 'age' => 16];
    protected function registerRoute()
    {
        /** @var HandlerMapping $router */
        $router = App::getBean('httpRouter');
        $router->get('/', function (Request $request) {
            return $this->result;
        });
    }

    /**
     * @test
     * @throws \Swoft\Exception\Exception
     */
    public function testEncrypt()
    {
        $response = $this->request('GET', '/');
        $res = $response->getBody()->getContents();
        /* @var EncryptHandler $encryptHandler*/
        $encryptHandler = App::getBean(EncryptHandler::class);
        $encryptData = $encryptHandler->encrypt(json_decode($res, true));
        $this->assertEquals($this->result, $encryptHandler->decrypt($encryptData));
    }

    /**
     * @test
     * @throws \Swoft\Exception\Exception
     */
    public function testSign()
    {
        $response = $this->request('GET', '/');
        $res = $response->getBody()->getContents();
        /* @var EncryptHandler $encryptHandler*/
        $encryptHandler = App::getBean(EncryptHandler::class);
        $encryptData = $encryptHandler->sign(json_decode($res, true));
        $this->assertEquals($this->result, $encryptHandler->verify(http_build_query($encryptData)));
    }
}