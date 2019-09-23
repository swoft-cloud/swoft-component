<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Stdlib\Swoole;

use Swoft\Http\Message\ContentType;
use Swoft\Http\Message\Response;
use Swoole\Coroutine\Http\Client;
use function array_merge;
use function http_build_query;
use function in_array;
use function is_array;
use function is_string;
use function json_encode;
use function parse_url;
use function preg_replace;
use function strpos;
use function strtoupper;

/**
 * Class HttpClient - an simple http client base on swoole http coroutine client
 */
class HttpClient
{
    private const DEFAULT_URL_DATA = [
        'scheme'   => 'http',
        'host'     => 'localhost',
        'port'     => '80',
        'user'     => '',
        'pass'     => '',
        'path'     => '/',
        'query'    => '',
        'fragment' => '',
    ];

    /**
     * @var array
     */
    private $options = [];

    /**
     * @var string
     */
    private $fullUrl = '';

    /**
     * @var array
     */
    private $rawResult = [
        'errCode' => 0,
        'errMsg'  => 0,
        'status'  => 200,
    ];

    /**
     * Send GET request
     *
     * @param string $url
     * @param array  $options
     *
     * @return Response
     */
    public function get(string $url, array $options = []): Response
    {
        return $this->request('GET', $url, $options);
    }

    /**
     * Send POST request
     *
     * @param string $url
     * @param mixed  $data
     * @param array  $options
     *
     * @return Response
     */
    public function post(string $url, $data, array $options = []): Response
    {
        $options['data'] = $data;

        return $this->request('POST', $url, $options);
    }

    /**
     * Send JSON request
     *
     * @param string $url
     * @param array  $data
     * @param array  $options
     *
     * @return Response
     */
    public function json(string $url, array $data, array $options = []): Response
    {
        $options['json'] = $data;

        return $this->request('POST', $url, $options);
    }

    /**
     * @param string $method
     * @param string $url
     * @param array  $options
     *
     * @return Response
     */
    public function request(string $method, string $url, array $options = []): Response
    {
        if (!isset($options['method'])) {
            $options['method'] = $method;
        }

        $info   = $this->parseUrl($url);
        $port   = (int)$info['port'];
        $client = new Client($info['host'], $port, $port === 443);

        // config request
        $uriPath = $this->configRequest($client, $info, $options);

        $client->execute($uriPath);

        $this->fullUrl   = $info['scheme'] . '://' . $info['host'] . $uriPath;
        $this->rawResult = [
            'errCode' => $client->errCode,
            'errMsg'  => $client->errMsg,
            'status'  => $client->statusCode,
        ];

        // trans to psr7 response
        $resp = new Response();
        $resp = $resp->withContent($client->body)->withStatus($client->statusCode);

        // close connection
        $client->close();

        return $resp;
    }

    /**
     * @param Client $client
     * @param array  $info
     * @param array  $options
     *
     * @return string
     */
    private function configRequest(Client $client, array $info, array $options): string
    {
        if ($this->options) {
            $options = array_merge($this->options, $options);
        }

        $uriPath  = $info['path'] . ($info['query'] ? '?' . $info['query'] : '');
        $method   = $options['method'] ?: 'GET';
        $headers  = $options['headers'] ?? [];
        $sendData = $options['data'] ?? null;

        // set request method
        $client->setMethod($method = strtoupper($method));

        // allow send data(POST, PUT, PATCH)
        if (in_array($method, ['POST', 'PUT', 'PATCH'], true)) {
            $postData = '';
            if ($jsonMap = $options['json'] ?? []) {
                $postData = json_encode($jsonMap);

                // add content type
                $headers['Content-Type'] = ContentType::JSON;
            } elseif ($formMap = $options['form'] ?? []) {
                $postData = http_build_query($formMap);

                // add content type
                $headers['Content-Type'] = ContentType::FORM;
            } elseif ($sendData) {
                if (is_array($sendData)) {
                    $postData = http_build_query($sendData);

                    // add content type
                    $headers['Content-Type'] = ContentType::FORM;
                } else {
                    $postData = (string)$sendData;
                }
            }

            // has post data
            if ($postData) {
                $client->setData($postData);
            }
        } elseif ($sendData) {
            $queryStr = $this->buildQuery($sendData);
            $uriPath  = $this->appendQueryString($uriPath, $queryStr);
        }

        // has query data
        if ($queryMap = $options['query'] ?? 0) {
            $queryStr = $this->buildQuery($queryMap);
            $uriPath  = $this->appendQueryString($uriPath, $queryStr);
        }

        /*
         * [key => value]
         */
        if ($headers) {
            $client->setHeaders($headers);
        }

        /*
         * [key => value]
         */
        if ($cookies = $options['cookies'] ?? []) {
            $client->setCookies($cookies);
        }

        /**
         * [
         *  timeout => 3 // 3 seconds
         *  keep_alive => true
         *  websocket_mask => true
         * ]
         *
         * more @see https://wiki.swoole.com/wiki/page/p-client_setting.html
         */
        if ($settings = $options['settings'] ?? []) {
            $client->set($settings);
        }

        return $uriPath;
    }

    /**
     * @param string $uriPath
     * @param string $queryString
     *
     * @return string
     */
    private function appendQueryString(string $uriPath, string $queryString): string
    {
        // check sep char
        $sepChar = strpos($uriPath, '?') > 0 ? '&' : '?';

        return $uriPath . $sepChar . $queryString;
    }

    /**
     * @param string $url
     *
     * @return array
     */
    public function parseUrl(string $url): array
    {
        $info = array_merge(self::DEFAULT_URL_DATA, parse_url($url));

        if ($info['scheme'] === 'https') {
            $info['port'] = 443;
        }

        return $info;
    }

    /**
     * @param array|string $queryData
     *
     * @return string
     */
    public function buildQuery($queryData): string
    {
        // is string
        if (is_string($queryData)) {
            return $queryData;
        }

        // array: k-v map
        return preg_replace('/%5B(?:\d|[1-9]\d+)%5D=/', '=', http_build_query($queryData));
    }

    /**
     * @return string
     */
    public function getFullUrl(): string
    {
        return $this->fullUrl;
    }

    /**
     * @return array
     */
    public function getRawResult(): array
    {
        return $this->rawResult;
    }

    /**
     * @param array $options
     *
     * @return HttpClient
     */
    public function setOptions(array $options): self
    {
        $this->options = $options;
        return $this;
    }
}
