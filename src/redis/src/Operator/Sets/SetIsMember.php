<?php

namespace Swoft\Redis\Operator\Sets;

use Swoft\Redis\Operator\Command;

class SetIsMember extends Command
{
    /**
     * [Set] sContains - sIsMember
     *
     * @return string
     */
    public function getId()
    {
        return 'sContains';
    }

    /**
     * @param string $data
     * @return bool
     */
    public function parseResponse($data)
    {
        return (bool)$data;
    }

}
