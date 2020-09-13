<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Server\Event;

use Swoft\Event\Event;
use Swoole\Server;

/**
 * Class ServerStartEvent
 *
 * @since 2.0
 */
class ServerStartEvent extends Event
{
    /**
     * @var Server;
     */
    public $coServer;

    /**
     * Class constructor.
     *
     * @param string $name
     * @param Server $server
     */
    public function __construct(string $name, Server $server)
    {
        parent::__construct($name);

        $this->coServer = $server;
    }
}
