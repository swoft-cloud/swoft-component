<?php

namespace Swoft\Trace\Bean\Annotation;

/**
 * Trace annotation
 *
 * @Annotation
 * @Target({"CLASS","METHOD"})
 */
class Trace
{
    /**
     * @var string
     */
    private $handler;

    /**
     * Trace annotation constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['handler'])) {
            $this->handler = $values['handler'];
        }
    }

    /**
     * @return string
     */
    public function getHandler(): string
    {
        return $this->handler;
    }

}
