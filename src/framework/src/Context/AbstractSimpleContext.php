<?php declare(strict_types=1);

namespace Swoft\Context;

use Swoft\Concern\SimpleDataPropertyTrait;

/**
 * Class AbstractSimpleContext
 *
 * @since 2.0
 */
abstract class AbstractSimpleContext implements ContextInterface
{
    use SimpleDataPropertyTrait;

    /**
     * Clear context data
     */
    public function clear(): void
    {
        $this->data = [];
    }
}
