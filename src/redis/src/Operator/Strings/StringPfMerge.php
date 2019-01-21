<?php


namespace Swoft\Redis\Operator\Strings;

use Swoft\Redis\Operator\Command;

class StringPfMerge extends Command
{
    /**
     * [String] pfMerge
     *
     * @return string
     */
    public function getId()
    {
        return 'pfMerge';
    }
}