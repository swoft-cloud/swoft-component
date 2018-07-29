<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Auth\Parser\Handler;

use Psr\Http\Message\ServerRequestInterface;
use Swoft\App;
use Swoft\Auth\Constants\AuthConstants;
use Swoft\Auth\Mapping\AuthManagerInterface;
use Swoft\Auth\Mapping\AuthHandlerInterface;
use Swoft\Bean\Annotation\Bean;

/**
 * Class BearerTokenParser
 * @package Swoft\Auth\Parser
 * @Bean()
 */
class BearerTokenHandler implements AuthHandlerInterface
{
    const NAME = 'Bearer';

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function handle(ServerRequestInterface $request): ServerRequestInterface
    {
        $token = $this->getToken($request);
        /** @var AuthManagerInterface $manager */
        $manager = App::getBean(AuthManagerInterface::class);
        if ($token) {
            $res = $manager->authenticateToken($token);
            $request = $request->withAttribute(AuthConstants::IS_LOGIN, $res);
        }
        return $request;
    }

    protected function getToken(ServerRequestInterface $request)
    {
        $authHeader = $request->getHeaderLine(AuthConstants::HEADER_KEY) ?? '';
        $authQuery = $request->getQueryParams()['token'] ?? '';
        return $authQuery ? $authQuery : $this->parseValue($authHeader);
    }

    protected function parseValue($string)
    {
        if (strpos(trim($string), self::NAME) !== 0) {
            return null;
        }
        return preg_replace('/.*\s/', '', $string);
    }
}
