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

use Swoft\Http\Message\ContentType;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;

/**
 * Class TestController
 *
 * @Controller("/fixture/test")
 */
class TestController
{
    /**
     * @RequestMapping("@prefix")
     * @return string
     */
    public function home(): string
    {
        return 'home';
    }

    /**
     * @RequestMapping()
     * @return string
     */
    public function hello(): string
    {
        return 'hello';
    }

    /**
     * @RequestMapping()
     *
     * @param Response $response
     *
     * @return Response
     */
    public function cookie(Response $response): Response
    {
        return $response->setCookie('ck', 'val')->withContent('hello');
    }

    /**
     * @RequestMapping()
     *
     * @param Response $response
     *
     * @return Response
     */
    public function htmlData(Response $response): Response
    {
        return $response->withData('<h1>hello</h1>')->withContentType(ContentType::HTML);
    }

    /**
     * @RequestMapping()
     *
     * @param Response $response
     *
     * @return Response
     */
    public function htmlContent(Response $response): Response
    {
        return $response->withContent('<h1>hello</h1>')->withContentType(ContentType::HTML);
    }
}
