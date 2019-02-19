<?php declare(strict_types=1);


namespace Swoft\Bean;

/**
 * Class PrototypeInterface
 *
 * @since 2.0
 */
interface PrototypeInterface
{
    /**
     * @param mixed ...$params
     *
     * @return static
     */
    public static function new(...$params);
}