<?php declare(strict_types=1);


namespace Swoft\Http\Server\Exception;

use Throwable;

/**
 * Class NotFoundRouteException
 *
 * @since 2.0
 */
class NotFoundRouteException extends HttpServerException
{
    /**
     * Class constructor.
     *
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct($message = '', $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
