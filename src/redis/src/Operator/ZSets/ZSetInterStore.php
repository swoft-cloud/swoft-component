<?php

namespace Swoft\Redis\Operator\ZSets;

use Swoft\Redis\Operator\Command;

class ZSetInterStore extends Command
{
    public function getId()
    {
        return 'ZInter';
    }
}