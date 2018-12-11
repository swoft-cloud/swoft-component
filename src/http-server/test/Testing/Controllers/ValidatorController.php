<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace SwoftTest\HttpServer\Testing\Controllers;

use Swoft\Http\Message\Server\Request;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Http\Server\Bean\Annotation\RequestMethod;
use Swoft\Bean\Annotation\Number;
use Swoft\Bean\Annotation\Strings;
use Swoft\Http\Message\Server\Response;

/**
 * Class ValidatorController
 * @Controller(prefix="/validator")
 */
class ValidatorController
{
    /**
     * @Number(name="test.id", max=10)
     * @Strings(name="test.name", default="limx")
     * @RequestMapping(route="json", method=RequestMethod::POST)
     */
    public function json(Request $request, Response $response)
    {
        $id = $request->json('test.id');
        $name = $request->json('test.name');

        return $response->json([$id, $name]);
    }

    /**
     * @RequestMapping(route="/", method={RequestMethod::POST,RequestMethod::GET,RequestMethod::PUT,RequestMethod::DELETE})
     * @param Request $request
     */
    public function index(Request $request, Response $response)
    {
        return $response->json([
            'headers' => [
                'Host' => $request->getHeaderLine('Host'),
                'User-Agent' => $request->getHeaderLine('User-Agent'),
                'Accept' => $request->getHeaderLine('Accept'),
                'Content-Type' => $request->getHeaderLine('Content-Type'),
            ],
            'server' => [],
            'method' => $request->getMethod(),
            'uri' => [
                'scheme' => $request->getUri()->getScheme(),
                'userInfo' => $request->getUri()->getUserInfo(),
                'host' => $request->getUri()->getHost(),
                'port' => $request->getUri()->getPort(),
                'path' => $request->getUri()->getPath(),
                'query' => $request->getUri()->getQuery(),
                'fragment' => $request->getUri()->getFragment(),
            ],
            'body' => $request->getBody()->getContents(),
        ]);
    }
}
