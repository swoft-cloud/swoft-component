<?php declare(strict_types=1);

namespace Swoft\Http\Message;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Concern\PrototypeTrait;
use Swoft\Bean\Exception\PrototypeException;
use Swoft\Http\Message\Concern\MessageTrait;
use Swoft\Http\Message\Contract\ResponseFormatterInterface;
use Swoft\Http\Message\Contract\ResponseInterface;
use Swoft\Http\Message\Stream\Stream;
use Swoole\Http\Response as CoResponse;

/**
 * Class Response
 *
 * @since 2.0
 * @Bean(name="httpResponse", scope=Bean::PROTOTYPE)
 */
class Response implements ResponseInterface
{
    use PrototypeTrait;

    /**
     * Message trait
     */
    use MessageTrait;

    /**
     * @var string
     */
    protected $reasonPhrase = '';

    /**
     * @var int
     */
    protected $statusCode = 200;

    /**
     * @var string
     */
    protected $charset = 'utf-8';

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * Original response data. When this is not null, it will be converted into stream content
     *
     * @var mixed
     */
    protected $data;

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
     * Create response replace of constructor
     *
     * @param CoResponse $coResponse
     *
     * @return static|Response
     * @throws PrototypeException
     */
    public static function new(CoResponse $coResponse): self
    {
        $instance             = self::__instance();
        $instance->coResponse = $coResponse;

        return $instance;
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
    public function send(): void
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
     * @throws PrototypeException
     */
    public function withContent($content): Response
    {
        if ($this->stream) {
            return $this;
        }

        $new = clone $this;

        $new->stream = Stream::new($content);
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

    /**
     * Retrieve attributes derived from the request.
     * The request "attributes" may be used to allow injection of any
     * parameters derived from the request: e.g., the results of path
     * match operations; the results of decrypting cookies; the results of
     * deserializing non-form-encoded message bodies; etc. Attributes
     * will be application and request specific, and CAN be mutable.
     *
     * @return array Attributes derived from the request.
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Retrieve a single derived request attribute.
     * Retrieves a single derived request attribute as described in
     * getAttributes(). If the attribute has not been previously set, returns
     * the default value as provided.
     * This method obviates the need for a hasAttribute() method, as it allows
     * specifying a default value to return if the attribute is not found.
     *
     * @see getAttributes()
     *
     * @param string $name    The attribute name.
     * @param mixed  $default Default value to return if the attribute does not exist.
     *
     * @return mixed
     */
    public function getAttribute($name, $default = null)
    {
        return array_key_exists($name, $this->attributes) ? $this->attributes[$name] : $default;
    }

    /**
     * Return an instance with the specified derived request attribute.
     * This method allows setting a single derived request attribute as
     * described in getAttributes().
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated attribute.
     *
     * @see getAttributes()
     *
     * @param string $name  The attribute name.
     * @param mixed  $value The value of the attribute.
     *
     * @return static
     */
    public function withAttribute($name, $value)
    {
        $clone                    = clone $this;
        $clone->attributes[$name] = $value;
        return $clone;
    }

    /**
     * Return instance with the specified data
     *
     * @param mixed $data
     *
     * @return static
     */
    public function withData($data)
    {
        $clone = clone $this;

        $clone->data = $data;
        return $clone;
    }

    /**
     * Gets the response status code.
     *
     * The status code is a 3-digit integer result code of the server's attempt
     * to understand and satisfy the request.
     *
     * @return int Status code.
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Return an instance with the specified status code and, optionally, reason phrase.
     *
     * If no reason phrase is specified, implementations MAY choose to default
     * to the RFC 7231 or IANA recommended reason phrase for the response's
     * status code.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated status and reason phrase.
     *
     * @link http://tools.ietf.org/html/rfc7231#section-6
     * @link http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
     *
     * @param int    $code         The 3-digit integer result code to set.
     * @param string $reasonPhrase The reason phrase to use with the
     *                             provided status code; if none is provided, implementations MAY
     *                             use the defaults as suggested in the HTTP specification.
     *
     * @return static
     * @throws \InvalidArgumentException For invalid status code arguments.
     */
    public function withStatus($code, $reasonPhrase = '')
    {
        $new             = clone $this;
        $new->statusCode = (int)$code;

        if ($reasonPhrase === '' && isset(self::PHRASES[$new->statusCode])) {
            $reasonPhrase = self::PHRASES[$new->statusCode];
        }

        $new->reasonPhrase = $reasonPhrase;
        return $new;
    }

    /**
     * Return an instance with the specified charset content type.
     *
     * @param $charset
     *
     * @return static
     * @throws \InvalidArgumentException
     */
    public function withCharset($charset): self
    {
        return $this->withAddedHeader('Content-Type', sprintf('charset=%s', $charset));
    }

    /**
     * Gets the response reason phrase associated with the status code.
     *
     * Because a reason phrase is not a required element in a response
     * status line, the reason phrase value MAY be null. Implementations MAY
     * choose to return the default RFC 7231 recommended reason phrase (or those
     * listed in the IANA HTTP Status Code Registry) for the response's
     * status code.
     *
     * @link http://tools.ietf.org/html/rfc7231#section-6
     * @link http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
     * @return string Reason phrase; must return an empty string if none present.
     */
    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getCharset(): string
    {
        return $this->charset;
    }

    /**
     * @param string $charset
     *
     * @return Response
     */
    public function setCharset(string $charset): Response
    {
        $this->charset = $charset;
        return $this;
    }

}