<?php

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