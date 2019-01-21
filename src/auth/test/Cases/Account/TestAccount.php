<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Auth\Account;

use Swoft\Auth\Bean\AuthResult;
use Swoft\Auth\Mapping\AccountTypeInterface;
use Swoft\Bean\Annotation\Bean;

/**
 * Class TestAccount
 * @package SwoftTest\Auth
 * @Bean()
 */
class TestAccount implements AccountTypeInterface
{
    /**
     * @param array $data Login data
     *
     * @return AuthResult|null
     */
    public function login(array $data): AuthResult
    {
        $name = $data[0] ?? '';
        $pw = $data[1] ?? '';
        $result = new AuthResult();
        if ($name !== '' && $pw !== '') {
            $result->setIdentity(1);
            $result->setExtendedData(['role'=>'test']);
        } else {
            $result->setIdentity(1);
        }
        return $result;
    }

    /**
     * @param string $identity Identity
     *
     * @return bool Authentication successful
     */
    public function authenticate(string $identity): bool
    {
        return true;
    }
}
