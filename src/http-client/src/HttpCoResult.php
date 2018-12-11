<?php
declare(strict_types=1);

/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\HttpClient;

use Psr\Http\Message\ResponseInterface;
use Swoft\Core\AbstractResult;
use Swoft\Http\Message\Stream\SwooleStream;
use Swoft\HttpClient\Adapter\ResponseTrait;

/**
 * Http Defer Result
 *
 * @property \Swoole\Http\Client|resource $connection
 */
class HttpCoResult extends AbstractResult implements HttpResultInterface
{
    use ResponseTrait;

    /**
     * Return result
     *
     * @param array $params
     * @return string
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getResult(...$params): string
    {
        $response = $this->getResponse(...$params);
        return $response->getBody()->getContents();
    }

    /**
     * @alias getResult()
     * @param array $params
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getResponse(...$params): ResponseInterface
    {
        $client = $this->connection;
        $this->recv();
        $result = $client->body;
        $client->close();
        $headers = value(function () {
            $headers = [];
            foreach ($this->connection->headers ?? [] as $key => $value) {
                $exploded = explode('-', $key);
                foreach ($exploded as &$str) {
                    $str = ucfirst($str);
                }
                $ucKey = implode('-', $exploded);
                $headers[$ucKey] = $value;
            }
            unset($str);
            return $headers;
        });
        $response = $this->createResponse()
                         ->withBody(new SwooleStream($result ?? ''))
                         ->withHeaders($headers ?? [])
                         ->withStatus($this->transferStatusCode($this->connection->statusCode));
        return $response;
    }

    /**
     * @param int $statusCode
     * @return int
     */
    private function transferStatusCode(int $statusCode): int
    {
        if ($statusCode === -1) {
            return 504;
        } elseif ($statusCode === -2) {
            return 408;
        } elseif ($statusCode === -3) {
            return 500;
        }
        return $statusCode > 0 ? $statusCode : 500;
    }
}
