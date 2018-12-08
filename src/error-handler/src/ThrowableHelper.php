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

namespace Swoft\ErrorHandler;

/**
 * Class ExceptionHelper
 *
 * @package Swoft\ErrorHandler
 */
class ThrowableHelper
{
    /**
     * @param \Throwable $throwable
     * @return array
     */
    public static function toArray(\Throwable $throwable): array
    {
        $previous = null;
        if ($throwable->getPrevious() instanceof \Throwable) {
            $previous = self::toArray($throwable->getPrevious());
        }
        return [
            'message' => $throwable->getMessage(),
            'code' => $throwable->getCode(),
            'file' => $throwable->getFile(),
            'line' => $throwable->getLine(),
            'trace' => $throwable->getTrace(),
            'previous' => $previous,
        ];
    }

    /**
     * @param \Throwable $throwable
     * @return array
     */
    public static function toArraySimple(\Throwable $throwable): array
    {
        return [
            'message' => $throwable->getMessage(),
            'code' => $throwable->getCode(),
            'file' => $throwable->getFile(),
            'line' => $throwable->getLine(),
        ];
    }
}
