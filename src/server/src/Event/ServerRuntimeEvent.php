<?php

namespace Swoft\Server\Event;

use Co\Server;
use Swoft\Event\Event;

/**
 * Class ServerRuntimeEvent
 * @since 2.0
 */
class ServerRuntimeEvent extends Event
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
