<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Http\Message\Contract;

use Psr\Http\Message\ResponseInterface;
use Swoft\Http\Message\Response;

/**
 * Class ResponseFormatterInterface
 *
 * @since 2.0
 */
interface ResponseFormatterInterface
{
    /**
     * @param Response $response
     *
     * @return Response|ResponseInterface
     */
    public function format(Response $response): Response;
}
