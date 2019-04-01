<?php

namespace Swoft\ErrorHandler;


/**
 * Interface HandlerInterface
 *
 * @package Swoft\ErrorHandler
 */
interface HandlerInterface
{

    /**
     * @param \Throwable $throwable
     * @return mixed
     */
    public function handle(\Throwable $throwable);
    
}