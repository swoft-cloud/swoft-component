<?php

namespace Swoft\Redis\Operator\Hashes;

use Swoft\Redis\Operator\Command;

class HashExists extends Command
{
    /**
     * [Hash] hExists
     *
     * @return string
     */
    public function getId()
    {
        return 'hExists';
    }

    public function parseResponse($data)
    {
        return (bool)$data;
    }
}
