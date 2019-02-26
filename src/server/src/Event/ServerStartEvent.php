<?php

namespace Swoft\Server\Event;

use Co\Server;
use Swoft\Event\Event;

/**
 * Class ServerStartEvent
 * @since 2.0
 */
class ServerStartEvent extends Event
{
    /**
     * @var Server;
     */
    public $coServer;

    public function __construct(string $name, Server $server)
    {
        parent::__construct($name);

        $this->coServer = $server;
    }
}
