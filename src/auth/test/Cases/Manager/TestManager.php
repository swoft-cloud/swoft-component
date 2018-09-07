<?php
/**
 * Created by PhpStorm.
 * User: sl
 * Date: 2018/9/7
 * Time: 下午5:56
 * @author April2 <ott321@yeah.net>
 */

namespace SwoftTest\Auth\Manager;

use Swoft\Auth\AuthManager;
use Swoft\Redis\Redis;
use SwoftTest\Auth\Account\TestAccount;

class TestManager extends AuthManager
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