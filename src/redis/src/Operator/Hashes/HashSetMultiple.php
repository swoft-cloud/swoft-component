<?php

namespace Swoft\Redis\Operator\Hashes;

use Swoft\Redis\Operator\Command;

class HashSetMultiple extends Command
{
    /**
     * [Hash] hMSet
     *
     * @return string
     */
    public function getId()
    {
        return 'hMSet';
    }

    /**
     * {@inheritdoc}
     */
    protected function filterArguments(array $arguments)
    {
        if (count($arguments) === 2 && is_array($arguments[1])) {
            $flattenedKVs = array($arguments[0]);
            $args = $arguments[1];

            foreach ($args as $k => $v) {
                $flattenedKVs[] = $k;
                $flattenedKVs[] = $v;
            }

            return $flattenedKVs;
        }

        return $arguments;
    }
}
