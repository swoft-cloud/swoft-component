<?php

namespace Swoft\Http\Message\Cookie;

use Swoft\Bean\Annotation\Bean;
use Swoft\Core\RequestContext;


/**
 * Class CookieManager
 * @Bean("cookieManager")
 *
 * @package Swoft\Http\Message\Cookie
 */
class CookieManager
{

    /**
     * Notice that the Cookies from the Container only Response Cookies,
     * Not include Request Cookies
     *
     * @return \ArrayIterator
     */
    public function all(): \ArrayIterator
    {
        return $this->getContainer();
    }

    /**
     * @param string $name
     * @param mixed  $value
     * @param int    $expire
     * @param string $path
     * @param string $domain
     * @param bool   $secure
     * @param bool   $httpOnly
     * @throws \InvalidArgumentException
     * @return void
     */
    public function add(
        string $name,
        $value,
        int $expire = 0,
        string $path = '/',
        string $domain = '',
        bool $secure = false,
        bool $httpOnly = true
    ) {
        if (! $domain) {
            $request = RequestContext::getRequest();
            $domain = $request->getUri()->getHost();
        }
        $cookie = new Cookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
        $this->getContainer()->offsetSet($name, $cookie);
    }

    /**
     * @param string $name
     * @return void
     */
    public function remove(string $name)
    {
        $this->getContainer()->offsetUnset($name);
    }

    /**
     * @return void
     */
    public function clear()
    {
        RequestContext::setContextDataByKey('cookie', null);
    }

    /**
     * @return \ArrayIterator
     */
    protected function getContainer(): \ArrayIterator
    {
        $list = RequestContext::getContextDataByKey('cookie');
        if (! $list instanceof \ArrayIterator) {
            $list = new \ArrayIterator();
            RequestContext::setContextDataByKey('cookie', $list);
        }
        return $list;
    }

}