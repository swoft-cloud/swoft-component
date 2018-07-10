<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Auth\Bean;

use Swoft\Bean\Annotation\Bean;
use Swoft\Bean\Annotation\Scope;

/**
 * Class AuthSession
 * @package Swoft\Auth\Bean
 * @Bean(scope=Scope::PROTOTYPE)
 */
class AuthSession
{
    /**
     * @var string User personal information credentials
     */
    protected $identity='';

    /**
     * @var string Login method name
     */
    protected $accountTypeName='';

    /**
     * @var string Authentication credentials
     */
    protected $token='';

    /**
     * @var int Creation time
     */
    protected $createTime=0;

    /**
     * @var int
     */
    protected $expirationTime=0;

    /**
     * @var array Expand data, define it yourself
     */
    protected $extendedData=[];

    /**
     * @return string
     */
    public function getIdentity(): string
    {
        return $this->identity;
    }

    /**
     * @param string $identity
     * @return AuthSession
     */
    public function setIdentity(string $identity)
    {
        $this->identity = $identity;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccountTypeName(): string
    {
        return $this->accountTypeName;
    }

    /**
     * @param string $accountTypeName
     * @return AuthSession
     */
    public function setAccountTypeName(string $accountTypeName)
    {
        $this->accountTypeName = $accountTypeName;
        return $this;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return AuthSession
     */
    public function setToken(string $token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return int
     */
    public function getCreateTime(): int
    {
        return $this->createTime;
    }

    /**
     * @param int $createTime
     * @return AuthSession
     */
    public function setCreateTime(int $createTime)
    {
        $this->createTime = $createTime;
        return $this;
    }

    /**
     * @return int
     */
    public function getExpirationTime(): int
    {
        return $this->expirationTime;
    }

    /**
     * @param int $expirationTime
     * @return AuthSession
     */
    public function setExpirationTime(int $expirationTime)
    {
        $this->expirationTime = $expirationTime;
        return $this;
    }

    /**
     * @return array
     */
    public function getExtendedData()
    {
        return $this->extendedData;
    }

    /**
     * @param  $extendedData
     * @return AuthSession
     */
    public function setExtendedData($extendedData)
    {
        $this->extendedData = $extendedData;
        return $this;
    }
}
