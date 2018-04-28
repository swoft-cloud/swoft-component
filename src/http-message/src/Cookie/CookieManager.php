<?php

namespace Swoft\Http\Message\Cookie;

use Psr\Http\Message\RequestInterface;
use Swoft\Bean\Annotation\Bean;
use Swoft\Bean\Annotation\Scope;


/**
 * Class CookieManager
 * @Bean(scope=Scope::PROTOTYPE)
 */
class CookieManager
{

    /**
     * @var \ArrayIterator
     */
    protected $container;

    /**
     * @var \Psr\Http\Message\RequestInterface
     */
    protected $request;

    /**
     * CookieManager constructor.
     */
    public function __construct()
    {
        $this->container = new \ArrayIterator();
    }

    /**
     * Notice that the Cookies from the Container only Response Cookies,
     * Not include Request Cookies
     *
     * @return iterable
     */
    public function all(): iterable
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
        if (! $domain && $this->hasRequest()) {
            $domain = $this->getRequest()->getUri()->getHost();
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
     * @return \ArrayIterator
     */
    public function getContainer(): \ArrayIterator
    {
        return $this->container;
    }

    /**
     * @param \ArrayIterator $container
     * @return $this
     */
    public function setContainer($container): self
    {
        $this->container = $container;
        return $this;
    }

    /**
     * @return \Psr\Http\Message\RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @return $this
     */
    public function setRequest($request): self
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasRequest(): bool
    {
        return $this->request instanceof RequestInterface;
    }

}