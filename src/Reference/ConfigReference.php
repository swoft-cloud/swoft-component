<?php

namespace Swoft\Reference;


use Swoft\Bean\ReferenceInterface;

/**
 * Class ConfigReference
 *
 * @since 2.0
 */
class ConfigReference implements ReferenceInterface
{
    /**
     * Get refence value
     *
     * @param $value
     *
     * @return mixed
     */
    public function getValue($value)
    {
        // Remove `config.`
        $values = explode('.', $value);
        array_shift($values);
        $value = implode('.', $values);

        return config($value);
    }
}