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
use Swoft\Auth\Constants\AuthConstants;
use Swoft\Auth\Mapping\AuthHandlerInterface;
use Swoft\Bean\Annotation\Bean;

/**
 * Class BasicAuthParser
 * @package Swoft\Auth\Parser
 * @Bean()
 */
class BasicAuthHandler implements AuthHandlerInterface
{
    const NAME = 'Basic';

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function handle(ServerRequestInterface $request): ServerRequestInterface
    {
        $authHeader = $request->getHeaderLine(AuthConstants::HEADER_KEY) ?? '';
        $basic = $this->parseValue($authHeader);
        if ($basic) {
            $request = $request
                ->withAttribute(AuthConstants::BASIC_USER_NAME, $this->getUsername($basic))
                ->withAttribute(AuthConstants::BASIC_PASSWORD, $this->getPassword($basic));
        }
        return $request;
    }

    protected function getUsername(array $basic)
    {
        return $basic[0]??'';
    }

    protected function getPassword(array $basic)
    {
        return $basic[1]??'';
    }

    protected function parseValue($string):array
    {
        if (strpos(trim($string), self::NAME) !== 0) {
            return null;
        }
        $val =  preg_replace('/.*\s/', '', $string);
        if (!$val) {
            return null;
        }
        return  explode(':', base64_decode($val));
    }
}
