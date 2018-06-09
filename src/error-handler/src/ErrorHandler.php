<?php

namespace Swoft\ErrorHandler;


/**
 * Class ErrorHandler
 *
 * @package Swoft\ErrorHandler
 */
class ErrorHandler
{

    public function handle(\Throwable $throwable)
    {
        try {
            $response = \bean(ErrorHandlerChain::class)->map(function (HandlerInterface $handler) use ($throwable) {
                // Handler matching
                if ($this->isMatch($handler, $throwable)) {
                    $response = $handler->handle($throwable);
                    if ($response) {
                        return $response;
                    }
                }
            });
        } catch (\Throwable $t) {
            $response = [
                'message' => $t->getMessage(),
                'code' => $t->getCode(),
                'file' => $t->getFile(),
                'line' => $t->getLine(),
                'trace' => $t->getTrace(),
                'previous' => $t->getPrevious(),
            ];
        }
        return $response;
    }

}