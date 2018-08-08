<?php

namespace Swoft\Redis\Operator\ZSets;

class ZSetRangeByScore extends ZSetRange
{
    /**
     * [ZSet] zRevRangeByScore
     *
     * @return string
     */
    public function getId()
    {
        return 'zRangeByScore';
    }

    /**
     * {@inheritdoc}
     */
    protected function prepareOptions($options)
    {
        return [$options];
    }

    /**
     * {@inheritdoc}
     */
    protected function withScores()
    {
        $arguments = $this->getArguments();
        if (isset($arguments[3]) && is_array($arguments[3]) && array_key_exists('withscores', $arguments[3])) {
            return true;
        }

        return false;
    }
}
