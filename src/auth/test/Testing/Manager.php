<?php
declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Auth\Testing;

use Swoft\Auth\AuthManager;
use Swoft\Redis\Redis;
use SwoftTest\Auth\Testing\Account as TestAccount;

class Manager extends AuthManager
{
    protected $cacheClass = Redis::class;

    protected $cacheEnable = true;

    public function testLogin(string $username, string $password)
    {
        return $this->login(TestAccount::class, [
            $username,
            $password
        ]);
    }
}
