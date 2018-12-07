<?php


namespace Swoft\Redis\Operator\Strings;

use Swoft\Redis\Operator\Command;

class StringPfAdd extends Command
{
    /**
     * [String] pfAdd
     *
     * @return string
     */
    public function getId()
    {
        return 'pfAdd';
    }
}