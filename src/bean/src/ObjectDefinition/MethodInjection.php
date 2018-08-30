<?php

namespace Swoft\Bean\ObjectDefinition;

class MethodInjection
{
    /**
     * @var string
     */
    private $methodName;

    /**
     * @var ArgsInjection[]
     */
    private $parameters;

    public function __construct(string $methodName, array $parameters)
    {
        $this->methodName = $methodName;
        $this->parameters = $parameters;
    }

    public function getMethodName(): string
    {
        return $this->methodName;
    }

    /**
     * @return ArgsInjection[]
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}
