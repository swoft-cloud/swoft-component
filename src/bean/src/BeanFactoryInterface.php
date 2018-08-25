<?php

namespace Swoft\Bean;

interface BeanFactoryInterface
{

    /**
     * Get bean from bean factory.
     */
    public static function getBean(string $name);

    /**
     * Is bean exist ?
     */
    public static function hasBean(string $name): bool;
}
