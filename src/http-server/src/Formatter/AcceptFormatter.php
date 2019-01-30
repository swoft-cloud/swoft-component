<?php declare(strict_types=1);


namespace Swoft\Http\Server\Formatter;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Http\Server\Response;

/**
 * Class AcceptFormatter
 *
 * @Bean("acceptFormatter")
 * @since 2.0
 */
class AcceptFormatter implements FormatterInterface
{
    /**
     * Formats
     *
     * @var array
     *
     * @example
     * [
     *   'application/json' => Response::FORMAT_JSON,
     *   'application/xml' => Response::FORMAT_XML,
     * ]
     */
    protected $formats = [];

    /**
     * @param Response $response
     *
     * @return Response
     */
    public function format(Response $response): Response
    {

    }
}