<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Console\Exception;

use Throwable;

/**
 * Class ConsoleErrorException
 *
 * @since 2.0.3
 */
class ConsoleErrorException extends ConsoleException
{
    /**
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     *
     * @throws ConsoleErrorException
     */
    public static function throw(string $message, int $code = 0, Throwable $previous = null): void
    {
        throw new self($message, $code, $previous);
    }
}
