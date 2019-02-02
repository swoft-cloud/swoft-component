<?php

namespace Swoft;

/**
 * Interface DefinitionInterface
 */
interface DefinitionInterface
{
    /**
     * Core bean definition
     *
     * @return array
     *
     * [
     *  'bean name' => [
     *      'class' => MyBean::class
     *      ...
     *  ]
     * ]
     */
    public function coreBean(): array;
}