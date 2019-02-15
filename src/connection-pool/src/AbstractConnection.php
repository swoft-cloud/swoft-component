<?php declare(strict_types=1);


namespace Swoft\Connection\Pool;

/**
 * Class AbstractConnection
 *
 * @since 2.0
 */
abstract class AbstractConnection implements ConnectionInterface
{
    public function getId(): string
    {
        // TODO: Implement getId() method.
    }

    public function release(): void
    {
        // TODO: Implement release() method.
    }

}