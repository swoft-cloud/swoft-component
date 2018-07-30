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
use Swoft\Auth\AuthUserService;
use Swoft\Auth\Mapping\AuthServiceInterface;
use Swoft\Http\Message\Server\Request;
use Swoft\Http\Server\Router\HandlerMapping;
use SwoftTest\Auth\AbstractTestCase;

class BearerTokenParserTest extends AbstractTestCase
{
    protected function registerRoute()
    {
        /** @var HandlerMapping $router */
        $router = App::getBean('httpRouter');
        $router->get('/test', function (Request $request) {
            /** @var AuthUserService $service */
            $service  = App::getBean(AuthServiceInterface::class);
            $session = $service->getSession();
            return ['id'=>$session->getIdentity()];
        });
    }

    /**
     * @test
     * @covers AuthManager::authenticateToken()
     * @covers BearerTokenHandler::handle()
     * @covers AuthUserService::getSession()
     */
    public function testHandle()
    {
        $jwt = new JWTTokenParserTest();
        $token = $jwt->testGetToken();
        $response = $this->request('GET', '/test', [], self::ACCEPT_JSON, ['Authorization' => 'Bearer ' . $token], 'test');
        $res  = $response->getBody()->getContents();
        $this->assertEquals(json_decode($res, true), ['id' => 1]);
    }
}
