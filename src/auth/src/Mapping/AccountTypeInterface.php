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

use Swoft\Auth\Bean\AuthResult;

interface AccountTypeInterface
{
    const LOGIN_IDENTITY = 'identity';

    const LOGIN_CREDENTIAL = 'credential';

    /**
     * @param array $data Login data
     *
     * @return AuthResult|null
     */
    public function login(array $data):AuthResult;

    /**
     * @param string $identity Identity
     *
     * @return bool Authentication successful
     */
    public function authenticate(string $identity) :bool ;
}
