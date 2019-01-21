<?php

namespace Swoft\Redis\Operator\PubSubs;

use Swoft\Redis\Operator\Command;

class PubSubSubscribe extends Command
{
    /**
     * [PubSub] subscribe
     *
     * @return string
     */
    public function getId()
    {
        return 'subscribe';
    }

    /**
     * {@inheritdoc}
     */
    protected function filterArguments(array $arguments)
    {
        return self::normalizeArguments($arguments);
    }
}
