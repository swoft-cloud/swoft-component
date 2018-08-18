<?php
/**
 * Created by PhpStorm.
 * User: zcm
 * Date: 2018/7/26
 * Time: 15:41
 */

namespace Swoft\Encrypt\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft\App;
use Swoft\Bean\Annotation\Bean;
use Swoft\Encrypt\Handler\EncryptHandler;
use Swoft\Encrypt\Mapping\EncryptHandlerInterface;
use Swoft\Http\Message\Middleware\MiddlewareInterface;
use Swoft\Http\Message\Server\Request;
use Swoft\Http\Message\Server\Response;
use Swoft\Http\Server\AttributeEnum;

/**
 * @Bean()
 * Class EncryptMiddleware
 * @package Swoft\Encrypt\Middleware
 */
class EncryptMiddleware implements MiddlewareInterface
{
    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws \Swoft\Exception\Exception
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /* @var EncryptHandler $encryptHandler*/
        $encryptHandler = App::getBean(EncryptHandler::class); // 因底层bug, 应注入EncryptHandlerInterface

        /* @var Request $request*/
        $parsedBody = $encryptHandler->decrypt($request->raw());
        if ($parsedBody){
            $request = $request->withParsedBody($parsedBody);
        }

        /* @var Response $response*/
        $response = $handler->handle($request);

        /* @var Response $response*/
        $data = $response->getAttributes()[AttributeEnum::RESPONSE_ATTRIBUTE]; //  因底层bug, 应为 $response->getBody()->getContents()
        $encryptData = $encryptHandler->encrypt($data);

        return $response->withAttribute(AttributeEnum::RESPONSE_ATTRIBUTE, $encryptData); //  因底层bug, 应为 $response->withContent(base64_encode($encryptData))
    }
}