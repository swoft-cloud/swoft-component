<?php declare(strict_types=1);

namespace Swoft\Contract;

/**
 * Interface DefinitionInterface
 * @since 2.0
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
    public function beans(): array;
}
