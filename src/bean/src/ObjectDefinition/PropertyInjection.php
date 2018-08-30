<?php

namespace Swoft\Bean\ObjectDefinition;

class PropertyInjection
{
    /**
     * @var string
     */
    private $propertyName;

    /**
     * @var mixed
     */
    private $value;

    /**
     * Is bean reference ?
     *
     * @var bool
     */
    private $ref;

    public function __construct(string $propertyName, $value, $ref = false)
    {
        $this->propertyName = $propertyName;
        $this->value = $value;
        $this->ref = $ref;
    }

    public function getPropertyName(): string
    {
        return $this->propertyName;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Is bean reference ?
     */
    public function isRef(): bool
    {
        return $this->ref;
    }
}
