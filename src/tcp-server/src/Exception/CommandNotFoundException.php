<?php declare(strict_types=1);

namespace Swoft\Tcp\Server\Exception;

use Throwable;

/**
 * Class CommandNotFoundException
 */
class CommandNotFoundException extends TcpServerException
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
