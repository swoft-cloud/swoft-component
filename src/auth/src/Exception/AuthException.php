<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Auth\Exception;

use Swoft\Exception\RuntimeException;
use Throwable;

/**
 * Class AuthException
 * @package Swoft\Auth\Exception
 */
class AuthException extends RuntimeException
{
    public function __construct(int $code = 0, string $message = '', Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
