<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Http\Server\Testing\Controller;

use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Http\Server\Annotation\Mapping\RequestMethod;
use function context;

/**
 * Class RouteController
 *
 * @since 2.0
 *
 * @Controller("testRoute")
 */
class RouteController extends BaseController
{
    use TraitController;

    /**
     * @RequestMapping("")
     *
     * @return string
     */
    public function testRoute(): string
    {
        return 'testRoute';
    }

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
        return context()->getRequest()->getParsedBody();
    }

    /**
     * @RequestMapping("method", method={RequestMethod::POST, RequestMethod::PUT})
     */
    public function method(): string
    {
        return 'post';
    }

    /**
     * @RequestMapping("search/{name}", method=RequestMethod::GET)
     * @param string $name
     *
     * @return string
     */
    public function search(string $name): string
    {
        return $name;
    }
}
