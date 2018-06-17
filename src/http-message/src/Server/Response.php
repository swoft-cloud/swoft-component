<?php

namespace Swoft\Http\Message\Server;

use Swoft\Contract\Arrayable;
use Swoft\Helper\JsonHelper;
use Swoft\Helper\StringHelper;
use Swoft\Http\Message\Cookie\Cookie;

/**
 * Class Response
 *
 * @package Swoft\Http\Message\Server
 */
class Response extends \Swoft\Http\Message\Base\Response
{
    /**
     * @var \Throwable|null
     */
    protected $exception;

    /**
     * Swoole Response
     *
     * @var \Swoole\Http\Response
     */
    protected $swooleResponse;

    /**
     * Response constructor.
     *
     * @param \Swoole\Http\Response $response
     */
    public function __construct(\Swoole\Http\Response $response)
    {
        $this->swooleResponse = $response;
    }

    /**
     * Redirect to a URL
     *
     * @param string   $url
     * @param null|int $status
     * @return static
     * @throws \InvalidArgumentException
     */
    public function redirect($url, $status = 302)
    {
        $response = $this;
        $response = $response->withAddedHeader('Location', (string)$url)->withStatus($status);
        return $response;
    }

    /**
     * Return a Raw content Response
     *
     * @param  string $data   The data
     * @param  int    $status The HTTP status code.
     * @return \Swoft\Http\Message\Server\Response when $data not jsonable
     * @throws \InvalidArgumentException
     */
    public function raw(string $data = '', int $status = 200): Response
    {
        $response = $this;

        // Headers
        $response = $response->withoutHeader('Content-Type')->withAddedHeader('Content-Type', 'text/plain');
        $this->getCharset() && $response = $response->withCharset($this->getCharset());

        // Content
        $data && $response = $response->withContent($data);

        // Status code
        $status && $response = $response->withStatus($status);

        return $response;
    }

    /**
     * Return a Json format Response
     *
     * @param  array|Arrayable $data            The data
     * @param  int             $status          The HTTP status code.
     * @param  int             $encodingOptions Json encoding options
     * @return static when $data not jsonable
     * @throws \InvalidArgumentException
     */
    public function json(array $data = [], int $status = 200, int $encodingOptions = JSON_UNESCAPED_UNICODE): Response
    {
        $response = $this;

        // Headers
        $response = $response->withoutHeader('Content-Type')->withAddedHeader('Content-Type', 'application/json');
        $this->getCharset() && $response = $response->withCharset($this->getCharset());

        // Content
        if ($data && ($this->isArrayable($data) || \is_string($data))) {
            \is_string($data) && $data = ['data' => $data];
            $content = JsonHelper::encode($data, $encodingOptions);
            $response = $response->withContent($content);
        } else {
            $response = $response->withContent('{}');
        }

        // Status code
        $status && $response = $response->withStatus($status);

        return $response;
    }

    /**
     * @param string|string $data
     * @param int $status
     * @return \Swoft\Http\Message\Server\Response
     * @throws \InvalidArgumentException
     */
    public function auto($data, int $status = 200): Response
    {
        // todo Content-type negotiate
        if ($this->isArrayable($data)) {
            return $this->json($data, $status);
        } elseif (\is_string($data)) {
            return $this->json(['data' => $data], $status);
        }
        return $this;
    }

    /**
     * Handle Response and send
     *
     * @throws \RuntimeException
     */
    public function send()
    {
        $response = $this;

        /**
         * Headers
         */
        // Write Headers to swoole response
        foreach ($response->getHeaders() as $key => $value) {
            $this->swooleResponse->header($key, implode(';', $value));
        }

        /**
         * Cookies
         */
        foreach ($this->cookies ?? [] as $domain => $paths) {
            foreach ($paths ?? [] as $path => $item) {
                foreach ($item ?? [] as $name => $cookie) {
                    if ($cookie instanceof Cookie) {
                        $this->swooleResponse->cookie($cookie->getName(), $cookie->getValue(), $cookie->getExpiresTime(), $cookie->getPath(), $cookie->getDomain(), $cookie->isSecure(), $cookie->isHttpOnly());
                    }
                }
            }
        }

        /**
         * Status code
         */
        $this->swooleResponse->status($response->getStatusCode());

        /**
         * Body
         */
        $this->swooleResponse->end($response->getBody()->getContents());
    }

    /**
     * @return null|\Throwable
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * @param \Throwable $exception
     * @return $this
     */
    public function setException(\Throwable $exception): self
    {
        $this->exception = $exception;
        return $this;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function isArrayable($value): bool
    {
        return \is_array($value) || $value instanceof Arrayable;
    }

    /**
     * @param string $accept
     * @param string $keyword
     * @return bool
     */
    public function isMatchAccept(string $accept, string $keyword): bool
    {
        return StringHelper::contains($accept, $keyword) === true;
    }
}
