<?php declare(strict_types=1);

namespace Swoft\Error\Contract;

/**
 * Interface ErrorHandlerInterface
 *
 * @since 1.0
 */
interface ErrorHandlerInterface
{
    /**
     * @return int
     */
    public function getType(): int;
}
