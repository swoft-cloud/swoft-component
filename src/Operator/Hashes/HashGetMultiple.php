<?php

namespace Swoft\Redis\Operator\Hashes;

use Swoft\Redis\Operator\Command;

class HashGetMultiple extends Command
{
    /**
     * [Hash] hMGet
     *
     * @return string
     */
    public function getId()
    {
        return 'hMGet';
    }

    /**
     * {@inheritdoc}
     */
    protected function filterArguments(array $arguments)
    {
        return self::normalizeVariadic($arguments);
    }
}
