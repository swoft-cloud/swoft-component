<?php declare(strict_types=1);


namespace Swoft\Http\Server;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Http\Message\Response as PsrResponse;
use Swoft\Http\Message\Stream\Stream;
use Swoole\Http\Response as CoResponse;
use Swoft\Stdlib\Arrayable;
use Swoft\Stdlib\Helper\JsonHelper;
use Swoft\Stdlib\Helper\Str;

/**
 * Class Response
 *
 * @Bean(name="httpResponse", scope=Bean::PROTOTYPE)
 *
 * @since 2.0
 */
class Response extends PsrResponse
{
    /**
     * Raw
     */
    const FORMAT_RAW = 'raw';

    /**
     * Html
     */
    const FORMAT_HTML = 'html';

    /**
     * Json
     */
    const FORMAT_JSON = 'json';

    /**
     * Xml
     */
    const FORMAT_XML = 'xml';

    /**
     * Exception
     *
     * @var \Throwable|null
     */
    protected $exception;

    /**
     * Coroutine response
     *
     * @var CoResponse
     */
    protected $coResponse;

    /**
     * Default format
     *
     * @var string
     */
    protected $format = self::FORMAT_JSON;

    /**
     * All formatters
     *
     * @var array
     *
     * @example
     * [
     *     Response::FORMAT_JSON => new FormatterInterface,
     *     Response::FORMAT_XML => new FormatterInterface,
     *     Response::FORMAT_RAW => new FormatterInterface
     * ]
     */
    public $formatters = [];

    /**
     * Cookie
     *
     * @var array
     */
    protected $cookies = [];

    /**
     * @param CoResponse $coResponse
     */
    public function initialize(CoResponse $coResponse)
    {
        $this->coResponse = $coResponse;
    }

    /**
     * Redirect to a URL
     *
     * @param string   $url
     * @param null|int $status
     *
     * @return static
     */
    public function redirect($url, $status = 302): self
    {
        $response = $this;
        $response = $response->withAddedHeader('Location', (string)$url)->withStatus($status);

        return $response;
    }

    /**
     * return a Raw format response
     *
     * @param  string $data   The data
     * @param  int    $status The HTTP status code.
     *
     * @return static when $data not jsonable
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function raw(string $data = '', int $status = 200): self
    {
        // Headers
        $response = $this;
        $response = $response->withoutHeader('Content-Type')->withAddedHeader('Content-Type', 'text/plain');
        $this->getCharset() && $response = $response->withCharset($this->getCharset());

        // Content
        $data && $response = $response->withContent($data);

        // Status code
        $status && $response = $response->withStatus($status);

        return $response;
    }

    /**
     * return a Json format response
     *
     * @param  array|Arrayable $data            The data
     * @param  int             $status          The HTTP status code.
     * @param  int             $encodingOptions Json encoding options
     *
     * @return static when $data not jsonable
     * @throws \InvalidArgumentException
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function json($data = [], int $status = 200, int $encodingOptions = JSON_UNESCAPED_UNICODE): self
    {
        $response = $this;

        // Headers
        $response = $response->withoutHeader('Content-Type')
            ->withAddedHeader('Content-Type', 'application/json');

        $this->getCharset() && $response = $response->withCharset($this->getCharset());

        // Content
        if ($data && ($this->isArrayable($data) || is_string($data))) {
            is_string($data) && $data = ['data' => $data];
            $content  = JsonHelper::encode($data, $encodingOptions);
            $response = $response->withContent($content);
        } else {
            $response = $response->withContent('{}');
        }

        // Status code
        $status && $response = $response->withStatus($status);

        return $response;
    }

    /**
     * Send response
     *
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function send()
    {
        $response = $this;

        // Write Headers to co response
        foreach ($response->getHeaders() as $key => $value) {
            $this->coResponse->header($key, implode(';', $value));
        }

        // Set code
        $this->coResponse->status($response->getStatusCode());

        // Set body
        $this->coResponse->end($response->getBody()->getContents());
    }

    /**
     * Return new response instance with content
     *
     * @param $content
     *
     * @return static
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function withContent($content): Response
    {
        if ($this->stream) {
            return $this;
        }

        /* @var Stream $stream */
        $stream = \bean(Stream::class);
        $stream->initialize($content);

        $new = clone $this;

        $new->stream = $stream;
        return $new;
    }

    /**
     * @return null|\Throwable
     */
    public function getException(): ?\Throwable
    {
        return $this->exception;
    }

    /**
     * @param \Throwable $exception
     *
     * @return $this
     */
    public function setException(\Throwable $exception): self
    {
        $this->exception = $exception;
        return $this;
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function isArrayable($value): bool
    {
        return is_array($value) || $value instanceof Arrayable;
    }

    /**
     * @param string $accept
     * @param string $keyword
     *
     * @return bool
     */
    public function isMatchAccept(string $accept, string $keyword): bool
    {
        return Str::contains($accept, $keyword) === true;
    }

    /**
     * @return CoResponse
     */
    public function getCoResponse(): CoResponse
    {
        return $this->coResponse;
    }

    /**
     * @param CoResponse $coResponse
     *
     * @return $this
     */
    public function setCoResponse(CoResponse $coResponse): self
    {
        $this->coResponse = $coResponse;
        return $this;
    }
}