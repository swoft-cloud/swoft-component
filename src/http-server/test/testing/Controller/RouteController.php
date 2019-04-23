<?php declare(strict_types=1);


namespace SwoftTest\Http\Server\Testing\Controller;


use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Http\Server\Annotation\Mapping\RequestMethod;

/**
 * Class RouteController
 *
 * @since 2.0
 *
 * @Controller("testRoute")
 */
class RouteController
{
    /**
     * @RequestMapping("string")
     *
     * @return string
     */
    public function string(): string
    {
        return 'string';
    }

    /**
     * @RequestMapping("array")
     *
     * @return array
     */
    public function arr(): array
    {
        return ['arr'];
    }

    /**
     * @RequestMapping(route="null")
     */
    public function null()
    {
        return null;
    }

    /**
     * @RequestMapping("data")
     * @return array
     */
    public function data(): array
    {
        return [
            'name' => 'swoft',
            'desc' => 'framework'
        ];
    }

    /**
     * @RequestMapping("parser")
     * @return array
     */
    public function parser(): array
    {
        $body = \context()->getRequest()->getParsedBody();
        return $body;
    }

    /**
     * @RequestMapping("method", method={RequestMethod::POST, RequestMethod::PUT})
     */
    public function method()
    {
        return 'post';
    }
}
