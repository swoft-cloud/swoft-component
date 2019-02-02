<?php declare(strict_types=1);


namespace Swoft\Http\Server;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Http\Message\Response as PsrResponse;
use Swoft\Http\Message\Stream\Stream;
use Swoft\Http\Server\Formatter\ResponseFormatterInterface;
use Swoole\Http\Response as CoResponse;

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
     * Html
     */
    const CONTENT_HTML = 'application/xml';

    /**
     * Json
     */
    const CONTENT_JSON = 'application/json';

    /**
     * Xml
     */
    const CONTENT_XML = 'application/xml';

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
     *     Response::FORMAT_JSON => new ResponseFormatterInterface,
     *     Response::FORMAT_XML => new ResponseFormatterInterface,
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
     * Send response
     *
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function send()
    {
        // Prepare
        $response = $this->prepare();

        // Write Headers to co response
        foreach ($response->getHeaders() as $key => $value) {
            $this->coResponse->header($key, implode(';', $value));
        }

        // Set code
        $this->coResponse->status($response->getStatusCode());

        // Set body
        $content = $response->getBody()->getContents();

        $this->coResponse->end($content);
    }

    /**
     * Prepare response
     *
     * @return Response
     */
    private function prepare(): Response
    {
        $formatter = $this->formatters[$this->format] ?? null;
        if (!empty($formatter) && $formatter instanceof ResponseFormatterInterface) {
            return $formatter->format($this);
        }

        return $this;
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

    /**
     * @param string $format
     */
    public function setFormat(string $format): void
    {
        $this->format = $format;
    }
}