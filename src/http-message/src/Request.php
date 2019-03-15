<?php declare(strict_types=1);

namespace Swoft\Http\Message;

use Psr\Http\Message\StreamInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Container;
use Swoft\Http\Message\Concern\InteractsWithInput;
use Swoft\Http\Message\Contract\RequestParserInterface;
use Swoft\Http\Message\Contract\ServerRequestInterface;
use Swoft\Http\Message\Stream\Stream;
use Swoft\Http\Message\Uri\Uri;
use Swoft\Http\Server\Helper\HttpHelper;
use Swoole\Http\Request as CoRequest;

/**
 * Class Request - The PSR ServerRequestInterface implement
 *
 * @since 2.0
 * @Bean(name="httpRequest", scope=Bean::PROTOTYPE)
 */
class Request extends PsrRequest implements ServerRequestInterface
{
    use InteractsWithInput;

    public const CONTENT_HTML = 'text/html';
    public const CONTENT_JSON = 'application/json';
    public const CONTENT_XML  = 'application/xml';

    private const METHOD_OVERRIDE_KEY = '_method';

    /**
     * @see $_SERVER
     * @var array
     */
    private const DEFAULT_SERVER = [
        'server_protocol'      => 'HTTP/1.1',
        'remote_addr'          => '127.0.0.1',
        'request_method'       => 'GET',
        'request_uri'          => '/',
        'request_time'         => 0,
        'request_time_float'   => 0,
        'query_string'         => '',
        'server_addr'          => '127.0.0.1',
        'server_name'          => 'localhost',
        'server_port'          => 80,
        'script_name'          => '',
        'https'                => '',
        'http_host'            => 'localhost',
        'http_accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        'http_accept_language' => 'en-US,en;q=0.8',
        'http_accept_charset'  => 'utf-8;q=0.7,*;q=0.3',
        'http_user_agent'      => 'Unknown',
    ];

    /**
     * @var CoRequest
     */
    protected $coRequest;

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @var array
     */
    protected $cookieParams = [];

    /**
     * @var null|array|object
     */
    private $parsedBody;

    /**
     * @var array
     */
    private $queryParams = [];

    /**
     * @var array
     */
    private $serverParams = [];

    /**
     * @var array
     */
    private $uploadedFiles = [];

    /**
     * @var float
     */
    private $requestTime = 0;

    /**
     * All parsers
     *
     * @var array
     *
     * @example
     * [
     *     'content-type' => new XxxParser(),
     *     'content-type' => new XxxParser(),
     *     'content-type' => new XxxParser(),
     * ]
     */
    private $parsers = [];

    /**
     * Create Psr server request from swoole request
     *
     * @param CoRequest $coRequest
     * @return Request
     */
    public static function new(CoRequest $coRequest): self
    {
        // on enter QPS: 4.5w
        // return new self(); // QPS: 4.4w
        $self = Container::$instance->getPrototype('httpRequest');
        // return $self; // QPS: 4.2w

        // SERVER data. swoole Request->server always exists
        // $serverParams = \array_change_key_case($coRequest->server, \CASE_UPPER);
        $serverParams = \array_merge(self::DEFAULT_SERVER, $coRequest->server);
        // return $self; // QPS: 3.7w

        $self->coRequest = $coRequest;
        // return $self; // QPS: 3.7w
        // Protocol version
        // $self->protocol = \str_replace('HTTP/', '', $serverParams['server_protocol']);
        $self->protocol = \substr($serverParams['server_protocol'], 5); // faster
        // return $self; // QPS: 3.7w

        // Set headers
        if ($headers = $coRequest->header) {
            // $self->setHeaders($headers); // QPS: 2.8w
            $self->setHeadersFromSwoole($headers); // QPS: 3.4w
        }
        // return $self;

        // Optimize: Don't create stream, init on first fetch
        // $self->body = Stream::new($content);
        $self->method = $serverParams['request_method'];

        $self->queryParams  = $coRequest->get ?: [];
        $self->cookieParams = $coRequest->cookie ?: [];
        $self->serverParams = $serverParams;
        $self->requestTime  = $serverParams['request_time_float'];

        $self->requestTarget = $serverParams['request_uri'];
        if ($coRequest->files) {
            $self->uploadedFiles = HttpHelper::normalizeFiles($coRequest->files);
        }
        // return $self; // QPS: 3.3w

        $self->uri = self::newUriByCoRequest($serverParams, $headers);
        // return $self; // QPS: 2.45w

        // Update uri by host
        if (!isset($headers['host'])) {
            $self->updateHostByUri();
        }

        return $self; // QPS: 2.44w
    }

    /**
     * Retrieve server parameters.
     * Retrieves data related to the incoming request environment,
     * typically derived from PHP's $_SERVER superGlobal. The data IS NOT
     * REQUIRED to originate from $_SERVER.
     *
     * @return array
     */
    public function getServerParams(): array
    {
        return $this->serverParams;
    }

    /**
     * Return an instance with the specified server params.
     *
     * @param array $serverParams
     *
     * @return static
     */
    public function withServerParams(array $serverParams)
    {
        $clone = clone $this;

        $clone->serverParams = $serverParams;
        return $clone;
    }

    /**
     * Retrieve cookies.
     * Retrieves cookies sent by the client to the server.
     * The data MUST be compatible with the structure of the $_COOKIE
     * superGlobal.
     *
     * @return array
     */
    public function getCookieParams(): array
    {
        return $this->cookieParams;
    }

    /**
     * @inheritdoc
     *
     * @return static
     */
    public function withCookieParams(array $cookies)
    {
        $clone = clone $this;

        $clone->cookieParams = $cookies;
        return $clone;
    }

    /**
     * @inheritdoc
     *
     * @return array
     */
    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    /**
     * add param
     *
     * @param string $name the name of param
     * @param mixed  $value the value of param
     *
     * @return static
     */
    public function addQueryParam(string $name, $value)
    {
        $clone = clone $this;

        $clone->queryParams[$name] = $value;
        return $clone;
    }

    /**
     * @inheritdoc
     *
     * @return static
     */
    public function withQueryParams(array $query)
    {
        $clone = clone $this;

        $clone->queryParams = $query;
        return $clone;
    }

    /**
     * @inheritdoc
     * @return array An array tree of UploadedFileInterface instances; an empty
     *     array MUST be returned if no data is present.
     */
    public function getUploadedFiles(): array
    {
        return $this->uploadedFiles;
    }

    /**
     * @inheritdoc
     *
     * @return static
     * @throws \InvalidArgumentException if an invalid structure is provided.
     */
    public function withUploadedFiles(array $uploadedFiles)
    {
        $clone = clone $this;

        $clone->uploadedFiles = $uploadedFiles;
        return $clone;
    }

    /**
     * Returns the raw HTTP request body.
     * @return string the request body
     */
    public function getRawBody(): string
    {
        return $this->coRequest->rawContent();
    }

    /**
     * @inheritdoc
     *
     * @return null|array|object The deserialized body parameters, if any.
     *     These will typically be an array or object.
     */
    public function getParsedBody()
    {
        // Need init
        if (null === $this->parsedBody) {
            $parsedBody = $coRequest->post ?? [];

            // Parse body
            if (!$parsedBody && !$this->isGet()) {
                $parsedBody = $this->parseRawBody($this->getRawBody());
            }

            $this->parsedBody = $parsedBody;
        }

        return $this->parsedBody;
    }

    /**
     * add parser body
     *
     * @param string $name the name of param
     * @param mixed  $value the value of param
     *
     * @return static
     */
    public function addParserBody(string $name, $value)
    {
        if (!\is_array($this->parsedBody)) {
            return $this;
        }

        $clone = clone $this;

        $clone->parsedBody[$name] = $value;
        return $clone;
    }

    /**
     * @inheritdoc
     * @return static
     * @throws \InvalidArgumentException if an unsupported argument type is provided.
     */
    public function withParsedBody($data)
    {
        $clone = clone $this;

        $clone->parsedBody = $data;
        return $clone;
    }

    /**
     * @inheritdoc
     *
     * @return array Attributes derived from the request.
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @inheritdoc
     * @see getAttributes()
     *
     * @param string $name The attribute name.
     * @param mixed  $default Default value to return if the attribute does not exist.
     *
     * @return mixed
     */
    public function getAttribute($name, $default = null)
    {
        return $this->attributes[$name] ?? $default;
    }

    /**
     * @inheritdoc
     *
     * @see getAttributes()
     *
     * @param string $name The attribute name.
     * @param mixed  $value The value of the attribute.
     *
     * @return static
     */
    public function withAttribute($name, $value)
    {
        $clone = clone $this;

        $clone->attributes[$name] = $value;
        return $clone;
    }

    /**
     * @inheritdoc
     *
     * @see getAttributes()
     *
     * @param string $name The attribute name.
     *
     * @return static
     */
    public function withoutAttribute($name)
    {
        if (!isset($this->attributes[$name])) {
            return $this;
        }

        $clone = clone $this;

        unset($clone->attributes[$name]);
        return $clone;
    }

    /**
     * Get the URL (no query string) for the request.
     *
     * @return string
     */
    public function url(): string
    {
        return \rtrim(\preg_replace('/\?.*/', '', $this->getUri()), '/');
    }

    /**
     * Get the full URL for the request.
     *
     * @return string
     */
    public function fullUrl(): string
    {
        $query    = $this->getUri()->getQuery();
        $question = $this->getUri()->getHost() . ($this->getUri()->getPath() === '/' ? '/?' : '?');
        return $query ? $this->url() . $question . $query : $this->url();
    }

    /**
     * @return string
     */
    public function getUriPath(): string
    {
        return $this->getUri()->getPath();
    }

    /**
     * Determine if the request is the result of an ajax call.
     *
     * @return bool
     */
    public function isAjax(): bool
    {
        return $this->isXmlHttpRequest();
    }

    /**
     * @inheritdoc
     * @see http://en.wikipedia.org/wiki/List_of_Ajax_frameworks#JavaScript
     *
     * @return bool true if the request is an XMLHttpRequest, false otherwise
     */
    public function isXmlHttpRequest(): bool
    {
        return 'XMLHttpRequest' === $this->getHeaderLine('X-Requested-With');
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        if ($method = $this->post(self::METHOD_OVERRIDE_KEY)) {
            return \strtoupper($method);
        }

        if ($method = $this->getHeaderLine('X-Http-Method-Override')) {
            return \strtoupper($method);
        }

        return parent::getMethod();
    }

    /**
     * @return StreamInterface
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function getBody(): StreamInterface
    {
        if (!$this->stream) {
            $this->stream = Stream::new($this->coRequest->rawContent());
        }

        return $this->stream;
    }

    /**
     * @return CoRequest
     */
    public function getCoRequest(): CoRequest
    {
        return $this->coRequest;
    }

    /**
     * @param CoRequest $coRequest
     */
    public function setCoRequest(CoRequest $coRequest): void
    {
        $this->coRequest = $coRequest;
    }

    /**
     * @return float
     */
    public function getRequestTime(): float
    {
        return $this->requestTime;
    }

    /**
     * @param string $content
     *
     * @return mixed
     */
    private function parseRawBody(string $content)
    {
        $contentTypes = $this->getHeader('Content-Type');

        foreach ($contentTypes as $contentType) {
            $parser = $this->parsers[$contentType] ?? null;
            if ($parser && $parser instanceof RequestParserInterface) {
                return $parser->parse($content);
            }
        }

        return $content;
    }

    /**
     * Get a Uri populated with values from $swooleRequest->server.
     *
     * @param array $server
     * @param array $header
     * @return Uri
     */
    public static function newUriByCoRequest(array &$server, array &$header): Uri
    {
        /** @var Uri $uri */
        $uri = Container::$instance->getPrototype(Uri::class);
        $uri = $uri->withScheme(isset($server['https']) && $server['https'] !== 'off' ? 'https' : 'http');

        $parts = \explode('?', $server['request_uri']);
        $uri   = $uri->withPath($parts[0]);

        $hasQuery = false;
        if (isset($parts[1])) {
            $hasQuery = true;
            /** @var Uri $uri */
            $uri = $uri->withQuery($parts[1]);
        }

        if (!$hasQuery && isset($server['query_string'])) {
            $uri = $uri->withQuery($server['query_string']);
        }

        $hasPort = false;
        if ($host = $server['http_host']) {
            $parts = \explode(':', $host);
            $uri   = $uri->withHost($parts[0]);
            if (isset($parts[1])) {
                $hasPort = true;
                $uri     = $uri->withPort($parts[1]);
            }
        } elseif ($host = $server['server_name'] ?: $server['server_addr']) {
            $uri = $uri->withHost($host);
        } elseif (isset($header['host'])) {
            if (\strpos($header['host'], ':')) {
                $hasPort = true;
                [$host, $port] = \explode(':', $header['host'], 2);
                if ($port !== '80') {
                    $uri = $uri->withPort($port);
                }
            } else {
                $host = $header['host'];
            }

            $uri = $uri->withHost($host);
        }

        if (!$hasPort && isset($server['server_port'])) {
            $uri = $uri->withPort($server['server_port']);
        }

        return $uri;
    }
}
