<?php

namespace Swoft\Redis\Aspect;

use Swoft\Aop\JoinPoint;
use Swoft\Aop\Bean\Annotation\AfterReturning;
use Swoft\Aop\Bean\Annotation\Aspect;
use Swoft\Aop\Bean\Annotation\Before;
use Swoft\Aop\Bean\Annotation\PointBean;
use Swoft\Log\Log;

/**
 * the aspect of redis
 *
 * @Aspect()
 * @PointBean({
 *     RedisCache::class
 * })
 */
class RedisAspect
{
    /**
     * the prefix of profile
     */
    const PROFILE_PREFIX = 'redis';

    /**
     * before of method
     *
     * @Before()
     * @param JoinPoint $joinPoint
     */
    public function before(JoinPoint $joinPoint)
    {
        $profileKey = $this->getProfileKey($joinPoint);
        Log::profileStart($profileKey);
    }

    /**
     * afterReturning of method
     *
     * @AfterReturning()
     * @param JoinPoint $joinPoint
     *
     * @return mixed
     */
    public function afterReturning(JoinPoint $joinPoint)
    {
        $profileKey = $this->getProfileKey($joinPoint);
        Log::profileEnd($profileKey);

        return $joinPoint->getReturn();
    }

    /**
     * the key of profile
     *
     * @param JoinPoint $joinPoint
     *
     * @return string
     */
    private function getProfileKey(JoinPoint $joinPoint)
    {
        $method = $joinPoint->getMethod();
        if ($method == '__call') {
            list($method) = $joinPoint->getArgs();
        }

        return self::PROFILE_PREFIX . '.' . $method;
    }
}
