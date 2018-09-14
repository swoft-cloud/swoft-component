<?php

namespace Swoft\Http\Message\Router;

interface HandlerMappingInterface
{
    /**
     * Get the handler of controller
     */
    public function getHandler(...$params): array;
}
