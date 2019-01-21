<?php

namespace Swoft\Bean;

/**
 * Interface ReferenceInterface
 */
interface ReferenceInterface
{
    /**
     * Get reference value
     *
     * @param $value
     *
     * @return mixed
     */
    public function getValue($value);
}