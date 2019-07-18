<?php declare(strict_types=1);

namespace Swoft\Context;

use Swoft\Concern\SimpleDataPropertyTrait;

/**
 * Class AbstractContext
 *
 * @since 2.0
 */
abstract class AbstractContext implements ContextInterface
{
    use SimpleDataPropertyTrait;

    /**
     * Clear
     */
    public function clear(): void
    {
        $this->data = [];
    }
}
