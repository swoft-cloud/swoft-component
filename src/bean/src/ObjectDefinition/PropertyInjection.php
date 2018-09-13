<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
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
