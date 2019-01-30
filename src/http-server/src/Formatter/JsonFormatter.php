<?php declare(strict_types=1);


namespace Swoft\Http\Server\Formatter;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Http\Server\Response;

/**
 * Class JsonFormatter
 *
 * @Bean()
 * @since 2.0
 */
class JsonFormatter implements FormatterInterface
{
    /**
     * Content type
     */
    const CONTENT_TYPE = 'application/json';

    /**
     * @param Response $response
     *
     * @return Response
     */
    public function format(Response $response): Response
    {

    }
}