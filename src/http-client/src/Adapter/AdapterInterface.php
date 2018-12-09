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

namespace Swoft\HttpClient\Adapter;

use Psr\Http\Message\RequestInterface;
use Swoft\HttpClient\HttpResultInterface;

/**
 * Http client adapter interface
 */
interface AdapterInterface
{
    /**
     * Send a http request
     *
     * @param RequestInterface $request
     * @param array            $options
     * @return HttpResultInterface
     */
    public function request(RequestInterface $request, array $options = []): HttpResultInterface;

    /**
     * Get the adapter default user agent
     *
     * @return string
     */
    public function getUserAgent(): string;
}
