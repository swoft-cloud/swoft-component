<?php

namespace Swoft\Redis\Operator\Servers;

use Swoft\Redis\Operator\Command;

class ServerEvalSHA extends ServerEval
{
    /**
     * [Server] evalSha
     *
     * @return string
     */
    public function getId()
    {
        return 'evalSha';
    }
}
