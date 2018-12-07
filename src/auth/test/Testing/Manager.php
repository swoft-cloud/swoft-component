<?php
/**
 * Created by PhpStorm.
 * User: limx
 * Date: 2018/12/7
 * Time: 9:57 AM
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