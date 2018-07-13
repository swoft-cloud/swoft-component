<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Auth\Parser;

use Swoft\App;
use Swoft\Auth\Constants\AuthConstants;
use Swoft\Http\Message\Server\Request;
use Swoft\Http\Server\Router\HandlerMapping;
use SwoftTest\Auth\AbstractTestCase;

/**
 * Class BasicAuthParserTest
 * @package SwoftTest\Auth\Parser
 */
class BasicAuthParserTest extends AbstractTestCase
{
    protected function registerRoute()
    {
        /** @var HandlerMapping $router */
        $router = App::getBean('httpRouter');
        $router->get('/', function (Request $request) {
            $name = $request->getAttribute(AuthConstants::BASIC_USER_NAME);
            $pd = $request->getAttribute(AuthConstants::BASIC_PASSWORD);
            return ['username' => $name, 'password' => $pd];
        });
    }

    /**
     * @test
     * @covers BasicAuthHandler::handle()
     */
    public function testHandle()
    {
        $username = 'user';
        $password = '123';
        $parser = base64_encode($username . ':' . $password);
        $response = $this->request('GET', '/', [], self::ACCEPT_JSON, ['Authorization' => 'Basic ' . $parser], 'test');
        $res = $response->getBody()->getContents();
        $this->assertEquals(json_decode($res, true), ['username' => $username, 'password' => $password]);
    }
}
