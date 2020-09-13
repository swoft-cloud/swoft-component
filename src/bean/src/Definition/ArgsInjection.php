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
 * Class ArgsInjection
 *
 * @since 2.0
 */
class ArgsInjection
{
    /**
     * Arg value
     *
     * @var mixed
     */
    private $value;

    /**
     * Is reference
     *
     * @var bool
     */
    private $isRef = false;

    /**
     * ArgsInjection constructor.
     *
     * @param mixed $value
     * @param bool  $isRef
     */
    public function __construct($value, bool $isRef)
    {
        $this->isRef = $isRef;
        $this->value = $value;
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
