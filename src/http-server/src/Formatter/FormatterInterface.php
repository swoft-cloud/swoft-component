<?php declare(strict_types=1);


namespace Swoft\Http\Server\Formatter;

use Swoft\Http\Server\Response;

/**
 * Class FormatterInterface
 *
 * @since 2.0
 */
interface FormatterInterface
{
    /**
     * @param Response $response
     *
     * @return Response
     */
    public function format(Response $response): Response;
}