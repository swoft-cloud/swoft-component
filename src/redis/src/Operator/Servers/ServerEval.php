<?php

namespace Swoft\Redis\Operator\Servers;

use Swoft\Redis\Operator\Command;

class ServerEval extends Command
{
    /**
     * [Server] eval
     *
     * @return string
     */
    public function getId()
    {
        return 'eval';
    }
}
