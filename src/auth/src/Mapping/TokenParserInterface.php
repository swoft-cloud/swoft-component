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
    public function getToken(AuthSession $session):string ;

    public function getSession(string $token):AuthSession ;
}
