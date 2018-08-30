<?php

namespace Swoft\Bean\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
class BootBean
{

    private $server = false;

    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->server = $values['value'];
        }
        if (isset($values['server'])) {
            $this->server = $values['server'];
        }
    }

    public function isServer(): bool
    {
        return $this->server;
    }
}