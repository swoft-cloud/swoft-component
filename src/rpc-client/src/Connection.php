<?php declare(strict_types=1);


namespace Swoft\Rpc\Client;


use Swoft\Connection\Pool\AbstractConnection;

/**
 * Class Connection
 *
 * @since 2.0
 */
class Connection extends AbstractConnection
{
    public function create(): void
    {

    }

    public function reconnect(): bool
    {
        
    }

    public function getLastTime(): int
    {
        return time();
    }

}