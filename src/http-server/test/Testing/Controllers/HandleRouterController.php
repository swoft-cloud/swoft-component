<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace SwoftTest\Testing\Controllers;

use Swoft\Http\Message\Server\Request;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Swoft\Http\Server\Bean\Annotation\RequestMethod;
use Swoft\Http\Message\Server\Response;

/**
 * Class ValidatorController
 * @Controller(prefix="/handle-router")
 */
class HandleRouterController
{
    /**
     * @RequestMapping(route="index", method=RequestMethod::POST)
     */
    public function index(Request $request, Response $response)
    {
        return $response->json([
            'status' => true
        ]);
    }

    /**
     * @RequestMapping(route="test", method=RequestMethod::POST)
     */
    public function test(Request $request, Response $response)
    {
        return $response->json([
            'status' => true,
            'route' => 'test'
        ]);
    }

    /**
     * @RequestMapping(route="test/", method=RequestMethod::POST)
     */
    public function test2(Request $request, Response $response)
    {
        return $response->json([
            'status' => true,
            'route' => 'test2'
        ]);
    }
}
