<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Auth\Mapping;

use Swoft\Auth\Bean\AuthSession;

interface TokenParserInterface
{
    /**
     * @param AuthSession $session
     * @return string
     */
    public function getToken(AuthSession $session):string ;

    /**
     * @param string $token
     * @return AuthSession
     */
    public function getSession(string $token):AuthSession ;
}
