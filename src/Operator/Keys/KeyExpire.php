<?php

namespace Swoft\Redis\Operator\Keys;

use Swoft\Redis\Operator\Command;

class KeyExpire extends Command
{
    /**
     * [Keys] expire
     *
     * @return string
     */
    public function getId()
    {
        return 'expire';
    }
}
