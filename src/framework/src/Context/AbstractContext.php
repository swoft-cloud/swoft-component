<?php declare(strict_types=1);

namespace Swoft\Context;

use Swoft\Concern\DataPropertyTrait;

/**
 * Class AbstractContext
 *
 * @since 2.0
 */
abstract class AbstractContext implements ContextInterface
{
    use DataPropertyTrait;

    /**
     * @return string
     */
    public function getParentId(): string
    {
        return $this->get('parentid', '');
    }

    /**
     * @return string
     */
    public function getTraceId(): string
    {
        return $this->get('traceid', '');
    }

    /**
     * @return string
     */
    public function getSpanId(): string
    {
        return $this->get('spanid', '');
    }
}
