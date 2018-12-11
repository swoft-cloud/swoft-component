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

namespace SwoftTest\Bean\Testing;

use Swoft\Bean\Annotation\Bean;
use Swoft\Bean\Annotation\Value;

/**
 * @Bean
 */
class ExampleConfig
{
    /**
     * @Value(env="${INT_VALUE}")
     * @var int
     */
    public $intValue;

    /**
     * @Value(env="${STRING_VALUE}")
     * @var string
     */
    public $stringValue;

    /**
     * @Value(env="${FLOAT_VALUE}")
     * @var float
     */
    public $floatValue;

    /**
     * @Value(env="${BOOL_VALUE}")
     * @var bool
     */
    public $boolValue;

    /**
     * @Value(env="${ARRAY_VALUE}")
     * @var array
     */
    public $arrayValue;

    /**
     * @return int
     */
    public function getIntValue(): int
    {
        return $this->intValue;
    }

    /**
     * @return string
     */
    public function getStringValue(): string
    {
        return $this->stringValue;
    }

    /**
     * @return float
     */
    public function getFloatValue(): float
    {
        return $this->floatValue;
    }

    /**
     * @return bool
     */
    public function isBoolValue(): bool
    {
        return $this->boolValue;
    }

    /**
     * @return array
     */
    public function getArrayValue(): array
    {
        return $this->arrayValue;
    }
}
