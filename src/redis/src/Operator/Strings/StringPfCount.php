<?php


namespace Swoft\Redis\Operator\Strings;

use Swoft\Redis\Operator\Command;

class StringPfCount extends Command
{
    /**
     * [String] pfCount
     *
     * @return string
     */
    public function getId()
    {
        return 'pfCount';
    }
}