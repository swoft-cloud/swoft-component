<?php declare(strict_types=1);


namespace SwoftTest\Http\Server\Unit\Server;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Concern\PrototypeTrait;
use Swoft\Stdlib\Helper\Arr;
use Swoole\Http\Request;

/**
 * Class MockRequest
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::PROTOTYPE)
 */
class MockRequest extends Request
{
    use PrototypeTrait;

    /**
     * Get
     */
    const GET = 'GET';

    /**
     * Post
     */
    const POST = 'POST';

    /**
     * @var int
     */
    public $fd = 1;

    /**
     * @var int
     */
    public $streamId = 1;

    /**
     * @var array
     */
    public $header = [];

    /**
     * @var array
     */
    public $server = [];

    /**
     * @var array
     */
    public $request = [];

    /**
     * @var array
     */
    public $cookie = [];

    /**
     * @var array
     */
    public $get = [];

    /**
     * @var array
     */
    public $files = [];

    /**
     * @var array
     */
    public $post = [];

    /**
     * @var array
     */
    public $tmpfiles = [];

    /**
     * @var string
     */
    public $content = '';

    /**
     * @param array $server
     * @param array $header
     * @param array $cookies
     * @param array $params
     *
     * @return MockRequest
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public static function new(array $server, array $header, array $cookies, array $params): self
    {
        $instance = self::__instance();

        $instance->cookie = $cookies;
        $instance->header = Arr::merge($header, self::defaultHeaders());
        $instance->server = Arr::merge($header, self::defaultServers());

        if ($server['request_method'] == self::GET) {
            $instance->get = $params;
        }

        if ($server['request_method'] == self::POST) {
            $instance->post = $params;
        }

        return $instance;
    }

    /**
     * @return string
     */
    public function rawContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $fd
     */
    public function setFd($fd): void
    {
        $this->fd = $fd;
    }

    /**
     * @param mixed $streamId
     */
    public function setStreamId($streamId): void
    {
        $this->streamId = $streamId;
    }

    /**
     * @param mixed $header
     */
    public function setHeader($header): void
    {
        $this->header = $header;
    }

    /**
     * @param mixed $server
     */
    public function setServer($server): void
    {
        $this->server = $server;
    }

    /**
     * @param mixed $request
     */
    public function setRequest($request): void
    {
        $this->request = $request;
    }

    /**
     * @param mixed $cookie
     */
    public function setCookie($cookie): void
    {
        $this->cookie = $cookie;
    }

    /**
     * @param mixed $get
     */
    public function setGet($get): void
    {
        $this->get = $get;
    }

    /**
     * @param mixed $files
     */
    public function setFiles($files): void
    {
        $this->files = $files;
    }

    /**
     * @param mixed $post
     */
    public function setPost($post): void
    {
        $this->post = $post;
    }

    /**
     * @param mixed $tmpfiles
     */
    public function setTmpfiles($tmpfiles): void
    {
        $this->tmpfiles = $tmpfiles;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }

    /**
     * @return array
     */
    public static function defaultHeaders(): array
    {
        return [
            'user-agent' => 'curl/7.29.0',
            'host'       => '127.0.0.1:18306',
            'accept'     => '*/*',
        ];
    }

    /**
     * @return array
     */
    public static function defaultServers(): array
    {
        return [
            'request_time'       => time(),
            'request_time_float' => microtime(true),
            'server_port'        => 18306,
            'remote_port'        => 56984,
            'remote_addr'        => '127.0.0.1',
            'master_time'        => 1555679772,
            'server_protocol'    => 'HTTP/1.1',
        ];
    }
}


//["fd"]=>
//  int(1)
//  ["streamId"]=>
//  int(0)
//  ["header"]=>
//  array(3) {
//    ["user-agent"]=>
//    string(11) "curl/7.29.0"
//    ["host"]=>
//    string(15) "127.0.0.1:18306"
//    ["accept"]=>
//    string(3) "*/*"
//  }
//  ["server"]=>
//  array(10) {
//    ["request_method"]=>
//    string(3) "GET"
//    ["request_uri"]=>
//    string(10) "/redis/str"
//    ["path_info"]=>
//    string(10) "/redis/str"
//    ["request_time"]=>
//    int(1555679772)
//    ["request_time_float"]=>
//    float(1555679772.5043)
//    ["server_port"]=>
//    int(18306)
//    ["remote_port"]=>
//    int(56984)
//    ["remote_addr"]=>
//    string(9) "127.0.0.1"
//    ["master_time"]=>
//    int(1555679772)
//    ["server_protocol"]=>
//    string(8) "HTTP/1.1"
//  }
