<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Bean\Definition;

/**
 * Class PropertyInjection
 *
 * @since 2.0
 */
class PropertyInjection
{
    /**
     * Property name.
     *
     * @var string
     */
    private $propertyName;

    /**
     * Value that should be injected in the property.
     *
     * @var mixed
     */
    private $value;

    /**
     * Is reference
     *
     * @var bool
     */
    private $isRef;

    /**
     * PropertyInjection constructor.
     *
     * @param string $propertyName
     * @param mixed  $value
     * @param bool   $isRef
     */
    public function __construct(string $propertyName, $value, bool $isRef)
    {
        $this->isRef        = $isRef;
        $this->value        = $value;
        $this->propertyName = $propertyName;
    }

    /**
     * @return string
     */
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
     * @return bool
     */
    public function isRef(): bool
    {
        return $this->isRef;
    }
}
