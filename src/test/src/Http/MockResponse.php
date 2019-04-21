<?php declare(strict_types=1);


namespace Swoft\Test\Http;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Concern\PrototypeTrait;
use Swoft\Test\Concern\HttpResponseAssertTrait;
use Swoole\Http\Response;

/**
 * Class MockResponse
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::PROTOTYPE)
 */
class MockResponse extends Response
{
    use PrototypeTrait, HttpResponseAssertTrait;

    /**
     * Status success
     */
    const STATUS_SUCCESS = 200;

    /**
     * @var string
     */
    private $content = '';

    /**
     * @var int
     */
    private $status = 0;

    /**
     * @return MockResponse
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public static function new()
    {
        $instance = self::__instance();

        return $instance;
    }

    /**
     * @param mixed $content
     *
     * @return void
     */
    public function end($content = null)
    {
        $this->content = $content;
    }

    /**
     * @param $html
     */
    public function write($html)
    {
    }

    /**
     * @param      $key
     * @param      $value
     * @param null $ucwords
     */
    public function header($key, $value, $ucwords = null)
    {
        $this->header[$key] = $value;
    }

    /**
     * @param string $name
     * @param string $value
     * @param int    $expires
     * @param string $path
     * @param string $domain
     * @param bool   $secure
     * @param bool   $httponly
     */
    public function cookie(
        $name,
        $value = null,
        $expires = null,
        $path = null,
        $domain = null,
        $secure = null,
        $httponly = null
    ) {
    }

    /**
     * 设置HttpCode，如404, 501, 200
     *
     * @param int    $code
     * @param string $reason
     */
    public function status($code, $reason = null)
    {
        $this->status = $code;
    }

    /**
     * 设置Http压缩格式
     *
     * @param int $level
     */
    public function gzip($level = 1)
    {
    }

    /**
     * 发送静态文件
     *
     * @param      $filename
     * @param null $offset
     * @param null $length
     *
     * @internal param string $level
     */
    public function sendfile($filename, $offset = null, $length = null)
    {

    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getFd()
    {
        return $this->fd;
    }

    /**
     * @return mixed
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @return mixed
     */
    public function getCookie()
    {
        return $this->cookie;
    }

    /**
     * @return mixed
     */
    public function getTrailer()
    {
        return $this->trailer;
    }

    /**
     * @param string $key
     * @param null   $default
     *
     * @return mixed
     */
    public function getHeaderKey(string $key, $default = null)
    {
        return $this->header[$key] ?? '';
    }
}