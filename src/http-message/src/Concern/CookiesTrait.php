<?php declare(strict_types=1);

namespace Swoft\Http\Message\Concern;

use function array_replace;
use function is_array;

/**
 * Trait CookiesTrait
 *
 * @since 2.0
 */
trait CookiesTrait
{
    /**
     * Default cookie properties
     *
     * @var array
     */
    private $cookieDefaults = [
        'value'    => '',
        'domain'   => null,
        'hostOnly' => null,
        'path'     => null,
        'expires'  => null,
        'secure'   => false,
        'httpOnly' => false
    ];

    /**
     * Cookie
     *
     * @var array[]
     */
    protected $cookies = [];

    /**
     * Set cookie
     *
     * @param string       $name  Cookie name
     * @param string|array $value Cookie value, or cookie properties
     *
     * @return $this
     */
    public function setCookie(string $name, $value): self
    {
        if (!is_array($value)) {
            $value = ['value' => (string)$value];
        }

        $this->cookies[$name] = array_replace($this->cookieDefaults, $value);

        return $this;
    }

    /**
     * Remove an cookie by name
     *
     * @param string $name
     *
     * @return self
     */
    public function delCookie(string $name): self
    {
        if (isset($this->cookies[$name])) {
            unset($this->cookies[$name]);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getCookies(): array
    {
        return $this->cookies;
    }

    /**
     * Set cookies
     *
     * @param array $cookies Cookies data. If is empty, will clear cookies
     *
     * @return self
     */
    public function setCookies(array $cookies): self
    {
        if (!$cookies) {
            $this->cookies = [];
            return $this;
        }

        foreach ($cookies as $name => $value) {
            $this->setCookie($name, $value);
        }

        return $this;
    }

}
