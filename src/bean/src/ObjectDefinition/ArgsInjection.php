<?php
declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Bean\ObjectDefinition;

class ArgsInjection
{
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

    public function __construct($value, bool $ref = false)
    {
        $this->value = $value;
        $this->ref = $ref;
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
