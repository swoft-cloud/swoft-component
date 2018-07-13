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

interface AuthManagerInterface
{
    /**
     * @param $accountTypeName
     * @param array $data
     * @return AuthSession
     */
    public function login(string $accountTypeName, array $data):AuthSession;

    /**
     * @param $token
     * @return bool
     */
    public function authenticateToken(string $token):bool ;
}
