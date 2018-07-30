<?php
namespace SwoftTest\Testing\Controllers;

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
}